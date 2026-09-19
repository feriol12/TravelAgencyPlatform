# Modèle de données conceptuel — V0.1

## Status
VALIDATED

## Core chain

User
-> ClientProfile
-> Beneficiary
-> TravelRequest
-> TravelProject

Around TravelProject:
- ProjectContact
- ProjectWorkflow -> WorkflowStep
- DocumentRequirement -> DocumentSubmission -> StoredFile
- FinancialAgreement -> Payment -> Receipt
- PaymentEvidence
- ProjectNote
- ProjectTimelineEvent
- AuditEvent

Transversal:
- AccountApplication
- ProcedureType
- PublicService
- Notification / NotificationDelivery
- LegalDocumentVersion / UserConsent
- Role / Permission
- AgencySetting
- ContactMessage
- ReferenceCounter

## Key concepts

`User`: identity/authentication.
`ClientProfile`: client business relationship.
`Beneficiary`: actual person concerned by a procedure.
`TravelRequest`: intent submitted to agency.
`TravelProject`: accepted request handled by agency.
`ExternalRequestSubmission`: isolates Google Forms/external intake.
`StoredFile`: physical file metadata separate from business meaning.
`AuditEvent`: internal immutable trace.
`ProjectTimelineEvent`: client-safe history.

## Validated decisions

- AccountApplication preserves repeated account applications.
- User and ClientProfile remain separate.
- Beneficiary supports parent/relative scenarios.
- ProcedureType remains distinct from marketing PublicService.
- Google Forms isolated through ExternalRequestSubmission.
- Accepted request creates TravelProject in DRAFT.
- ProjectContact belongs to project.
- One ProjectWorkflow abstraction with many WorkflowSteps.
- DocumentRequirement -> DocumentSubmission -> StoredFile.
- FinancialAgreement isolates agreed amount.
- Receipt distinct from PaymentEvidence.
- Notification event/channel concepts separated.
- Legal document versions and user consent are traceable.
- MVP remains mono-agency.
- Progress/financial totals are derived, not manually edited.
- Delete policies are decided relationship by relationship.
- REQ/PRJ/REC generation must be atomic.
