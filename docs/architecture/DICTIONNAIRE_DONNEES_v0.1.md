# Dictionnaire de données & matrice des relations — V0.1

## Status
VALIDATED

This file is the pre-migration reference. Detailed fields are expected to be
translated into Laravel migrations phase by phase.

## Core tables

### users
Identity/authentication.
Key fields: first_name, last_name, email UNIQUE, phone, password,
account_status, email_verified_at, last_login_at, deleted_at.

### account_applications
Each application decision is preserved.
No soft delete.

### client_profiles
One per approved client user.

### beneficiaries
Many per client profile; one may represent `is_self`.

### procedure_types
Internal procedure taxonomy.

### public_services
Public marketing content; independent from procedure types.

### travel_requests
Reference UNIQUE; client, beneficiary, procedure type, lifecycle and decision metadata.

### external_request_submissions
External intake source such as Google Forms.

### travel_projects
Reference UNIQUE; originating travel request UNIQUE; client, beneficiary,
responsible staff and lifecycle.

### project_contacts
Project-specific contact(s), one primary through business rule + tests.

### project_workflows / workflow_steps
One main workflow per project in MVP.

### document_requirements
What the agency expects.

### document_submissions
Versioned submissions. No ordinary deletion.

### stored_files
Central physical file metadata.

### financial_agreements
One current agreement per project.

### payments
VALID / CANCELLED. Historical correction by cancellation + replacement.

### receipts
Reference UNIQUE; at most one official receipt per payment.

### payment_evidences
Evidence files separate from official receipt.

### project_notes / client_notes
Internal notes.

### project_timeline_events
Client-safe project history.

### notifications / notification_deliveries
Laravel notifications + delivery/channel tracking.

### audit_events
Internal immutable audit; no soft delete.

### legal_document_versions / user_consents
Versioned legal documents and acceptance history.

### roles / permissions
RBAC + fine-grained permissions.

### agency_settings
Single-agency configuration.

### contact_messages
Public contact form; no private document upload.

### reference_counters
Atomic counters for REQ/PRJ/REC.

## Core relationship policy

Prefer RESTRICT for sensitive business history.

Use SET NULL for former staff authors/responsibles where appropriate.

CASCADE only for strictly dependent technical children and only after explicit justification.

## Implementation phases

A. Identity
B. Requests
C. Projects
D. Documents
E. Finance
F. Transversal

Each phase must include database integrity tests.
