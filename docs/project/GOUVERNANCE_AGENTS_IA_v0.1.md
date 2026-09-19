# Gouvernance des agents IA — V0.1

## Status
VALIDATED

## Core operating model

Important cards define:

- Human Owner
- AI Implementer
- AI Reviewer
- AI Tester

Default flow:

READY
-> IN PROGRESS
-> AI REVIEW
-> TESTING
-> HUMAN GATE
-> MERGE
-> DONE

BLOCKED may be used at any stage.

Humans orchestrate agent transitions initially.

## Required common files

- `/AGENTS.md`
- `docs/ai/PROJECT_CONTEXT.md`
- `docs/ai/CURRENT_STATE.md`
- `docs/ai/HANDOFF.md`
- `docs/ai/REVIEW_PROTOCOL.md`
- `docs/ai/SECURITY_RULES.md`

## Operational rules

- Card = task contract.
- Branch + base commit required for important cards.
- Implementer does not self-approve.
- Reviewer normally does not modify code.
- Reports use exact SHA.
- Human Gate required before important merge.
- Significant features use `feature -> develop -> main`.
- Prefer `--no-ff` for important feature merges.
- Use `SPECIFICATION GAP`, `ARCHITECTURE CHANGE REQUEST`,
  `DEPENDENCY REQUEST`, `OUT-OF-SCOPE FINDING` when needed.
- Migrations classified as ADDITIVE / ALTERING / DESTRUCTIVE.
- Destructive production changes require explicit human approval.
- Parallel work should use Git Worktree, isolated DBs and separate ports.
- WIP: 1–2 active implementation cards per human developer.
- Handoff required before changing agent on unfinished work.
- Do not depend on AI chat history as project memory.
- Major architecture decisions use ADRs.
- Provider-specific instruction files remain lightweight.
- Done = implementation + review + tests + human gate + merge.
