
Technical Specification: Sports Betting & Event Management System
System Overview
This system manages the lifecycle of sports events, from configuration and market creation to result processing. The architecture follows a hierarchical structure: Category → Tournament → Event → Market → Outcome.

Core Schema Structure
1. Hierarchy & Events
Categories: The top-level taxonomy (e.g., specific sports). All subsequent entities are scoped within a category.

Tournaments: Represents a specific competition series.

Linked to Categories via category_id.

Linked to TournamentConfig to enforce rules and settings.

Events: Individual matches or races.

Linked to Tournaments via tournament_id.

Contains start_date, end_date, and status.

Linked to Participants via event_participants (many-to-many relationship).

2. Market Logic
Markets: Betting opportunities linked to an Event (event_id).

Driven by MarketTemplates (market_template_id).

MarketTemplates: Blueprint for market creation.

Scoped by category_id.

market_type_id: Defines how the market is constructed.

outcome_type_ids: Determines the allowed outcome types.

param1: Configurable integer (e.g., number of participants to customer's select for stake : 1 for "Winner", 3 for "Podium").

param2: Reserved for future logic.

MarketTypes: Defines the business logic for templates:

1 (Boolean): Yes/No outcomes.

2 (Participant-based Boolean): Yes/No, but the statement incorporates a specific participant name.

3 (Selection participants): Outcomes are a list of participants.

4 (Binary Selection): Two outcomes, both are participants.

5 (Selection outcome types): Outcomes are a list of outcome_types 

3. Outcomes
Outcomes: Specific betting options within a market.

Linked to Markets via market_id.

outcome_type_id: Linked to OutcomeTypes (e.g., Yes, No, Participant).

participant_id: References the specific participant if outcome_type is Participant (type 3).

coef: Payout multiplier (odds).

result: Tracks the outcome status (win, lose, return).

4. Results Processing
Results: Recorded post-event to determine payouts.

Linked to Event via event_id.

result_type_id: Defines the metric (e.g., Race Winner, Best Lap, Qualification Winner).

value: JSON storage — format depends on value_type: positions → [participant_id, ...] (index = place, 0 = 1st); participant → not used (stored in participant_id column); score → {"score": "X:Y"}.

ResultTypes: Configurable result definitions scoped by category_id.

value_type: Dictates format (e.g., positions for JSON array, or participant for a single ID reference).

Agent Guidelines
Context Enforcement: Always verify the category_id when creating or validating Tournaments, TournamentConfigs, MarketTemplates, or ResultTypes.

Market Construction: When generating markets, strictly adhere to the logic defined by MarketType (e.g., ensure correct outcome_type_ids are used based on the template).

Data Integrity: When processing results, parse the value_type from ResultTypes to correctly interpret the results.value JSON or results.participant_id field.

Relationship Mapping: Always reference the join tables (event_participants, market_templates) to maintain relational integrity across the hierarchy.
