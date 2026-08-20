# Medinext Solutions Image Manifest

## 1. Directory Structure & Current State
The website uses three main directories for image assets:
- `/assets/images/` - Contains general site images like logos and EHR logos.
- `/assets/images/content/` - Contains images used in the `services.php` page service cards and blog.
- `/assets/images/services/` - Contains images used in the `index.php` homepage service cards.

## 2. Image References in Service Cards

### index.php (Homepage Services)
All referenced images currently **exist** in `/assets/images/services/`:
- `therapy-billing.jpg` (Therapies)
- `pain-management.jpg` (Pain Management)
- `cardiology-billing.jpg` (Cardio Health)
- `oncology-hematology.jpg` (Oncology-Hematology)
- `podiatry-billing.jpg` (Podiatry)
- `behavioral-health.jpg` (Behavioral Health)
- `dermatology-billing.jpg` (Dermatology)
- `specialty-billing.jpg` (Other Services)

### services.php (Services Page)

**Medical Billing Services (All Exist in `/assets/images/content/`):**
- `therapy-billing.jpg` (Therapy Billing)
- `pain-management.jpg` (Pain Management)
- `cardiovascular-billing.jpg` (Cardiovascular)
- `oncology-hematology.jpg` (Oncology-Hematology)
- `behavioral-health-svc.jpg` (Behavioral Health)
- `dme-billing.jpg` (DME Billing)
- `neurology-billing.jpg` (Neurology Billing)
- `radiology-billing.jpg` (Radiology Billing)
- `revenue-cycle-management.jpg` (Revenue Cycle Management)
- `denial-management.jpg` (Denial Management)
- `prior-authorization.jpg` (Prior Authorization)
- `medical-coding.jpg` (Medical Coding)
- `provider-credentialing.jpg` (Provider Credentialing)
- `occupational-therapy.jpg` (Occupational Therapy)

**Dental Billing Services:**
- `dental-billing.jpg` (Dental Billing) - **Exists**
- `dental-insurance.jpg` (Dental Insurance Verification) - **MISSING**
- `fee-schedule.jpg` (Fee Schedule Maintenance) - **MISSING**
- `ar-followup.jpg` (AR Follow Up) - **MISSING**
- `denial-management-dental.jpg` (Payment & Denial Management) - **MISSING**
- `credentialing-dental.jpg` (Dental Credentialing) - **MISSING**

## 3. Missing Images to be Created
The following images need to be generated for the Dental Billing Services section in `/assets/images/content/`:

1. **dental-insurance.jpg**
   - **Specialty/Topic:** Dental Insurance Verification
   - **Ideal Image Content:** A dental professional or administrative staff member reviewing insurance forms or eligibility verification on a modern screen, with a clean, trustworthy dental office background.
2. **fee-schedule.jpg**
   - **Specialty/Topic:** Fee Schedule Maintenance
   - **Ideal Image Content:** Close-up of financial planning, charts, or a dental professional comparing fee schedules. Emphasize optimization and profitability in a dental context.
3. **ar-followup.jpg**
   - **Specialty/Topic:** Accounts Receivable Follow Up
   - **Ideal Image Content:** An organized healthcare billing office environment, perhaps someone reviewing an aging report, emphasizing collections and diligent follow-up.
4. **denial-management-dental.jpg**
   - **Specialty/Topic:** Payment & Denial Management
   - **Ideal Image Content:** Focus on financial reconciliation, payment posting, or a determined staff member working on an appeal for a dental claim. High-tech and accurate.
5. **credentialing-dental.jpg**
   - **Specialty/Topic:** Dental Credentialing
   - **Ideal Image Content:** Official documents, a stethoscope/dental tools alongside enrollment paperwork, representing PPO/Medicaid enrollment and CAQH profile setup for dentists.

## 4. Recommended Image Specifications
- **Aspect Ratio:** 16:9 (Explicitly defined in CSS `.svc-card-img-wrap { aspect-ratio: 16/9; }`)
- **Dimensions:** 800x450 pixels or 1920x1080 pixels (downscaled for web)
- **Format:** `.jpg` (as referenced in the PHP files)
- **Style/Tone:** Professional, clean, modern medical/dental environments, well-lit, conveying trust, accuracy, and expertise.
