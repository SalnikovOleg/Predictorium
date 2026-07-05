- Create migration and model Page for table pages:
id	bigint autoincrement
model_type	varchar(255)
model_id	unsignedBigInteger
lang	varchar(2)	'en', 'uk' 
title	string(255)
content	longText.
Create index on ('model_type', 'model_id', 'lang')

- Add to models Category, Tournament, Event 
public function contents(): MorphMany
{
    return $this->morphMany(Pages::class, 'model');
}
- create enum language (en, uk)

- In Filament Resources  CategoryResource, TournamentResource, EventResource add one RelationManager
and use Repeater for add title and content for each of existed lang (en, uk)   
