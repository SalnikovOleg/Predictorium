interface IProps{
  status: string
  className?: string
}

export function EventIcon({status}:IProps) {
  const iconName = status == 'active' ? 'icon_active' : 'icon_flags';
  return (
    <div className="flex h-14 w-14 ">
      <img src={`/public/assets/icons/${iconName}.png`}/>
    </div>
  )
}

export function TournamentIcon() {
    const iconName = "icon_cup";
    return (
    <div className="flex h-14 w-14 ">
      <img src={`/public/assets/icons/${iconName}.png`}/>
    </div>
  )
}