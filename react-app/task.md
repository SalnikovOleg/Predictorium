install shadcn/ui.
Create page CategoryPage.
It calls by route path="/categories/:slug/
Getting data by /api/categories/:slug/
Response :
{
  "data": {
    "id": 1,
    "name": "Simracing",
    "slug": "simracing",
    "is_active": true,
    "sort_order": 1,
    "icon": "categories/icons/01KXB8TQBS7TH3T3NW68TERY0P.png",
    "contents": [],
    "tournaments": [
      {
        "id": 1,
        "name": "Warm Up League GT4 Summer Cup 2026",
        "slug": "war_up_gt4",
        "description": "Assetto Corsa Competizione solo event starting on Aug 03 at 8:00pm with 5 Rounds.",
        "status": "active",
        "start_date": "2026-08-03 08:00",
        "end_date": "2026-07-31 20:42"
      },
      {
        "id": 2,
        "name": "SKF LMU Proto-GT Series",
        "slug": "skf_lmu_proto_gt",
        "description": "Le Mans Ultimate solo event starting on Jul 11 at 7:00pm with 5 Rounds.",
        "status": "active",
        "start_date": "2026-07-11 07:00",
        "end_date": "2026-08-08 21:00"
      },
      {
        "id": 3,
        "name": "GTUKR iRacing Special",
        "slug": "gtukr_iracing_special",
        "description": "iRacing solo event that is completed with 1 Round.",
        "status": "active",
        "start_date": "2026-06-28 19:50",
        "end_date": "2026-06-28 21:00"
      }
    ]
  },
  "status": true
}
Use Tailwind, shadcn/ui.
Create reusable ui elements.
Follow .mimocode/skills/react-architect/SKILL.md