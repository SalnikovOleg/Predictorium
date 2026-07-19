# UI Architecture and Design Separation Rules

To simplify future design changes and theme customization, the project must strictly separate structure from visual styling.

## Core Principle

All design-related styling must be centralized inside the `/components/ui` layer.

Feature components must be responsible only for content structure, layout composition, and business logic. They should not define the visual identity of the application.

This architecture allows the entire design system to be modified by updating reusable UI components without changing feature implementations.

## Directory Responsibilities

### `/components/ui`

Contains all reusable design system components and visual primitives.

Responsibilities include:
- Colors
- Backgrounds
- Borders
- Shadows
- Typography styles
- Spacing standards
- Visual effects
- Theme support
- Interactive states (hover, focus, active)
- Animations
- Card appearances
- Buttons
- Form controls

Any styling that defines the application's visual identity belongs here.

### `/components/ui/common.tsx`

Contains simple shared UI elements used across the application, such as:
- H1
- H2
- ErrorMessage
- Background
- Header
- Other common typography and layout primitives

These components should encapsulate their own visual styling and provide a consistent appearance throughout the application.

### `/features/*`

Feature modules must contain only structural and domain-specific components.

Responsibilities include:
- Data presentation
- Content composition
- Layout structure
- Business logic
- State management
- User interactions

Feature components should avoid implementing custom visual styling when a reusable UI component already exists.

## Example: Tournament Card

### Correct approach

**`/features/tournament/ui/TournamentCard.tsx`**

Responsible for:
- Organizing tournament information
- Displaying title, dates, status, and metadata
- Defining content structure

Visual styling should be delegated to reusable UI components.

Example:
```tsx
<Card>
    <TournamentContent />
</Card>
```

**`/components/ui/card.tsx`**

Responsible for:
- Border design
- Background design
- Shadows
- Border radius
- Hover effects
- Theme integration
- Overall card appearance

Any changes to the card's visual design should be made only in this component.

## Design Consistency Rule

When creating new features:

1. First check whether an appropriate UI component already exists in `/components/ui`.
2. If the feature requires a new visual pattern, create or extend a reusable UI component.
3. Avoid feature-specific styling that duplicates design system functionality.
4. Keep feature components focused on structure and content.
5. Keep design decisions centralized in the UI layer.

## Goal

A designer or AI agent should be able to significantly change the application's appearance by modifying components inside `/components/ui` while leaving feature modules untouched.
