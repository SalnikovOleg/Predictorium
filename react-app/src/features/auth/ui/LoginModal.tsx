import { useState } from 'react'
import { useLogin } from '../hooks/useLogin'
import { useRegister } from '../hooks/useRegister'
import { ButtonPrimary } from '@/components/ui/buttons'
import {H2, H3} from '@/components/ui/common'

interface LoginModalProps {
  open: boolean
  onClose: () => void
}

export function LoginModal({ open, onClose }: LoginModalProps) {
  const [mode, setMode] = useState<'login' | 'register'>('login')
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [passwordConfirmation, setPasswordConfirmation] = useState('')

  const loginMutation = useLogin()
  const registerMutation = useRegister()

  const isLoading = loginMutation.isPending || registerMutation.isPending
  const error = loginMutation.error || registerMutation.error

  if (!open) return null

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    if (mode === 'login') {
      loginMutation.mutate({ email, password }, {
        onSuccess: () => {
          resetAndClose()
        },
      })
    } else {
      registerMutation.mutate(
        { name, email, password, password_confirmation: passwordConfirmation },
        {
          onSuccess: () => {
            resetAndClose()
          },
        },
      )
    }
  }

  const resetAndClose = () => {
    setName('')
    setEmail('')
    setPassword('')
    setPasswordConfirmation('')
    onClose()
  }

  const toggleMode = () => {
    setMode(mode === 'login' ? 'register' : 'login')
  }

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
      onClick={onClose}
    >
      <div
        className="w-full max-w-md rounded-lg border border-teal-500/30 bg-[#0d2a32] p-6 shadow-xl"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="mb-6 flex items-center justify-between">
          <H3 className="text-2xl">
            {mode === 'login' ? 'Login' : 'Register'}
          </H3>
          <button
            type="button"
            onClick={onClose}
            className="text-gray-400 hover:text-white"
          >
            <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        {error && (
          <div className="mb-4 rounded border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-400">
            {error.message || 'An error occurred'}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          {mode === 'register' && (
            <div>
              <label htmlFor="name" className="mb-1 block text-sm text-gray-300">
                Name
              </label>
              <input
                id="name"
                type="text"
                value={name}
                onChange={(e) => setName(e.target.value)}
                required
                className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
                placeholder="Your name"
              />
            </div>
          )}

          <div>
            <label htmlFor="email" className="mb-1 block text-sm text-gray-300">
              Email
            </label>
            <input
              id="email"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
              placeholder="your@email.com"
            />
          </div>

          <div>
            <label htmlFor="password" className="mb-1 block text-sm text-gray-300">
              Password
            </label>
            <input
              id="password"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              minLength={5}
              className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
              placeholder="Min. 5 characters"
            />
          </div>

          {mode === 'register' && (
            <div>
              <label htmlFor="password_confirmation" className="mb-1 block text-sm text-gray-300">
                Confirm Password
              </label>
              <input
                id="password_confirmation"
                type="password"
                value={passwordConfirmation}
                onChange={(e) => setPasswordConfirmation(e.target.value)}
                required
                minLength={8}
                className="w-full rounded border border-teal-500/20 bg-[#0a1e24] px-3 py-2 text-white placeholder-gray-500 focus:border-teal-500 focus:outline-none"
                placeholder="Confirm password"
              />
            </div>
          )}

          <ButtonPrimary
            type="submit"
            loading={isLoading}
            fullWidth
          >
            {mode === 'login' ? 'Login' : 'Register'}
          </ButtonPrimary>
        </form>

        <p className="mt-4 text-center text-sm text-gray-400">
          {mode === 'login' ? "Don't have an account?" : 'Already have an account?'}{' '}
          <button
            type="button"
            onClick={toggleMode}
            className="text-[val(--color-accent)] hover:underline"
          >
            {mode === 'login' ? 'Register' : 'Login'}
          </button>
        </p>
      </div>
    </div>
  )
}
