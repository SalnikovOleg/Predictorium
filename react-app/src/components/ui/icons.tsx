interface IProps{
  categorySlug: string
  status: string
  className?: string
}

export function EventIcon({categorySlug, status}:IProps) {
  const iconName = `${categorySlug}_${status}`;
  return (
    <div className="flex h-12 w-12 ">
      <img src={`/public/assets/icons/${iconName}.png`}/>
    </div>
  )
}

export function TournamentIcon() {
    const iconName = "icon_cup";
    return (
    <div className="flex h-10 w-10 ">
      <img src={`/public/assets/icons/${iconName}.png`}/>
    </div>
  )
}