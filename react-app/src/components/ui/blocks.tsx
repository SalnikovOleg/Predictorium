import { PeriodLine } from "@/components/ui/date"

interface ComponentProps {
  children:  React.ReactNode
  content?: string
  className?: string
}

export function SimpleAsideBlock({children }: ComponentProps) {
  return (
    <>
      <div className="relative w-full border rounded-2xl border-teal-500/50 bg-[#0a1e24]/40">
        <div className="absolute inset-0 z-10 bg-no-repeat" 
            style={{ backgroundImage: "url('/public/assets/asaid_block/left_top.png')" }} 
        />
        <div className="absolute z-10 top-0 right-0 w-[80px] h-[100px] bg-no-repeat bg-right bg-top" 
            style={{ backgroundImage: "url('/public/assets/asaid_block/right_top.png')" }} 
        />
        <div className="absolute z-10 inset-0 bg-no-repeat bg-bottom" 
            style={{ backgroundImage: "url('/public/assets/asaid_block/bottom.png')" }} 
        />
        {children}
      </div>
    </>
  )
}

export function CategoryAsideBlock({children, content }: ComponentProps) {
  return (
    <>
      <SimpleAsideBlock>
        {children}
      </SimpleAsideBlock>

      <div className="rounded-xl border border-teal-500/50 mt-[-30px] border-t-0 bg-[#0a1e24]/40 p-4 pb-6 pt-12">
        <div
          className="text-lg lg:text-sm max-w-none"
          dangerouslySetInnerHTML={{ __html: content ?? '' }}
          />
      </div>
    </>
  )
}

interface TournamentAsideBlockProps {
  children: React.ReactNode
  description?: string | null
  start_date: string
  end_date: string
  className?: string
}

export function TournamentAsideBlock({ children, description, start_date, end_date}: TournamentAsideBlockProps) {
  return (
    <>
      <SimpleAsideBlock>
        {children}
      </SimpleAsideBlock>

      <div className="rounded-xl border border-teal-500/50 mt-[-30px] border-t-0 bg-[#0a1e24]/40 p-4 pb-6 pt-12">
        {description && (
          <p className="text-lg lg:text-sm max-w-none mb-4">
            {description}
          </p>
        )}

        <PeriodLine start_date={start_date} end_date={end_date} />
      </div>
    </>
  )
}