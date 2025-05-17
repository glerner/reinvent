# Data Relationships: Reinvent Coaching Process Plugin

This document summarizes the relationships between the main data models in the Reinvent Coaching Process Plugin.

## Overview Diagram

```
Person
  │
  └── Journey
        │
        ├── Journey_Question
        └── Journey_Answer
```

## Table of Relationships

| Table/Model        | References                  | Description                                      |
|-------------------|----------------------------|--------------------------------------------------|
| Person_Profile    | —                          | Main entity representing the guided person        |
| Journey           | Person_Profile (person_id)  | A reinvention journey for a person               |
| Journey_Question  | —                          | Questions used in the journey process            |
| Journey_Answer    | Journey (journey_id),       | Answers to questions for a journey               |
|                   | Person_Profile (person_id), |                                                  |
|                   | Journey_Question (question_id)|                                              |

## Notes
- Each `Person_Profile` can have multiple `Journey` records.
- Each `Journey` can have multiple `Journey_Answer` records.
- Each `Journey_Answer` is linked to a `Journey_Question`.
- `Journey_Question` is a static set of questions, referenced by answers.

This file is for architectural clarity only—see code-inventory.md for naming and signature details.
