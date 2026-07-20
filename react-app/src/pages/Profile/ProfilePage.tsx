import { useState } from 'react'
import { useNavigate } from 'react-router'
import { useAuthStore, useUpdateUser } from '@/features/auth'
import { H1, H2, H3, ErrorMessage } from '@/components/ui/common'
import { ButtonPrimary, ButtonLogout, ButtonSecondary } from '@/components/ui/buttons'

export function ProfilePage() {
  const navigate = useNavigate()
  const user = useAuthStore((s) => s.user)
  const isAuthenticated = useAuthStore((s) => s.isAuthenticated)
  const openLoginModal = useAuthStore((s) => s.openLoginModal)
  const logout = useAuthStore((s) => s.logout)
  const updateUser = useUpdateUser()

  const [name, setName] = useState(user?.name ?? '')
  const [email, setEmail] = useState(user?.email ?? '')
  const [currentPassword, setCurrentPassword] = useState('')
  const [newPassword, setNewPassword] = useState('')
  const [passwordConfirmation, setPasswordConfirmation] = useState('')
  const [confirming, setConfirming] = useState(false)

  if (!isAuthenticated || !user) {
    openLoginModal()
    return <ErrorMessage message="Please log in to view your profile." />
  }

  const buildPayload = (): Record<string, string> => {
    const payload: Record<string, string> = {}
    if (name !== user.name) payload.name = name
    if (email !== user.email) payload.email = email
    if (newPassword) {
      payload.password = newPassword
      payload.password_confirmation = passwordConfirmation
      payload.current_password = currentPassword
    }
    return payload
  }

  const submitPayload = () => {
    updateUser.mutate(buildPayload(), {
      onSuccess: () => {
        setCurrentPassword('')
        setNewPassword('')
        setPasswordConfirmation('')
        setConfirming(false)
      },
      onError: () => {
        setConfirming(false)
      },
    })
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setConfirming(true)
  }

  return (
    <>
    <div className="space-y-6">
      <H1>Profile</H1>

      <div className="grid gap-8 lg:grid-cols-[1fr_340px]">
          <H2>{user.name}</H2>

        <aside>
          <div className="space-y-4">
          <div className="rounded-xl border border-teal-500/50 bg-[#0a1e24]/40 p-6">
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label htmlFor="profile-name" className="mb-1 block text-sm text-gray-300">
                  Name
                </label>
                <input
                  id="profile-name"
                  type="text"
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  required
                  className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
                />
              </div>

              <div>
                <label htmlFor="profile-email" className="mb-1 block text-sm text-gray-300">
                  Email
                </label>
                <input
                  id="profile-email"
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                  className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
                />
              </div>

              <div className="h-px bg-teal-500/30" />

              <div>
                <label htmlFor="profile-current-password" className="mb-1 block text-sm text-gray-300">
                  Current Password
                </label>
                <input
                  id="profile-current-password"
                  type="password"
                  value={currentPassword}
                  onChange={(e) => setCurrentPassword(e.target.value)}
                  className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
                  placeholder="Leave blank to keep current"
                />
              </div>

              <div>
                <label htmlFor="profile-new-password" className="mb-1 block text-sm text-gray-300">
                  New Password
                </label>
                <input
                  id="profile-new-password"
                  type="password"
                  value={newPassword}
                  onChange={(e) => setNewPassword(e.target.value)}
                  minLength={5}
                  className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
                  placeholder="Min. 5 characters"
                />
              </div>

              <div>
                <label htmlFor="profile-password-confirmation" className="mb-1 block text-sm text-gray-300">
                  Confirm New Password
                </label>
                <input
                  id="profile-password-confirmation"
                  type="password"
                  value={passwordConfirmation}
                  onChange={(e) => setPasswordConfirmation(e.target.value)}
                  minLength={5}
                  className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
                  placeholder="Confirm new password"
                />
              </div>

              {updateUser.isError && (
                <p className="text-sm text-red-400">
                  {(updateUser.error as { response?: { data?: { message?: string } } })?.response?.data?.message ?? 'Update failed'}
                </p>
              )}

              {updateUser.isSuccess && (
                <p className="text-sm text-green-400">Saved!</p>
              )}

              <ButtonPrimary
                type="submit"
                loading={updateUser.isPending}
                loadingText="Saving..."
                fullWidth
              >
                Save Changes
              </ButtonPrimary>
            </form>
          </div>

          <ButtonLogout onClick={() => { logout(); navigate('/') }} />
        </div>

        </aside>
      </div>
    </div>

      {confirming && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
          <div className="w-full max-w-sm rounded-xl border border-[--color-border]/60 bg-[#0a1e24] p-6 shadow-2xl">
            <H3 className="mb-2">Confirm Save</H3>
            <p className="text-sm text-gray-400 mb-6">Are you sure you want to save these changes to your profile?</p>
            <div className="flex gap-3">
              <ButtonSecondary className="flex-1" onClick={() => setConfirming(false)}>
                Cancel
              </ButtonSecondary>
              <ButtonPrimary
                className="flex-1"
                onClick={submitPayload}
                loading={updateUser.isPending}
                loadingText="Saving..."
              >
                Confirm
              </ButtonPrimary>
            </div>
          </div>
        </div>
      )}
    </>
  )
}
