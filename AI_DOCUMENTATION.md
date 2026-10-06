# IT415 — AI Collaboration & Adaptation Documentation

**PROJECT**: CampusGo — Touchscreen Self-Service Point-of-Sale (POS) Kiosk  
**TEAM**: Team Roar  
* **Member 1**: Earl Masana (`@url-izx`) — Backend & Database Architecture
* **Member 2**: Honey Jean Ambaic (`@hanixsyyy`) — Frontend Kiosk Engine & Keypad Logic
* **Member 3**: Althea Clariz Compoc (`@teiyahh`) — UI Theme, Receipt Printing & Documentation
**COURSE**: IT415 Practical Examination  
**DATE**: October 6, 2026  

---

## 📌 Executive Summary

In compliance with the IT415 Practical Examination guidelines, this document provides formal evidence of AI usage during the engineering of the **CampusGo POS Kiosk**. It captures:
1. Specific AI prompts submitted across development stages.
2. AI-generated architectural and design outputs.
3. Developer evaluation of AI recommendations.
4. Concrete modifications and adaptations implemented by the developers.
5. AI-assisted debugging workflows.
6. AI-assisted refactoring milestones.

---

## 1. Development Stages & Prompt Logs

### Stage 1: Requirements Breakdown & Sample UI Analysis
* **Developer Prompt**:
  > *"Analyze the IT415 Practical Exam requirements and the IT415 - Sample UI PDF screenshots. Break down the design language, product cards, cart interactions, 4-step sequence (1 Order -> 2 Review -> 3 Payment -> 4 Receipt), and touchscreen constraints. Do not start coding yet."*
* **AI Output Summary**:
  The AI produced a screen-by-screen architectural blueprint, noting the dark navy header, orange buttons, light background, pastel product cards with selection count badges, the live cart sidebar, the review table with non-destructive back navigation, cash keypad with change formula, simulated QR/Card flows, and thermal digital receipt.
* **Developer Evaluation**:
  The developers recognized that while the sample UI used an orange/navy theme, a campus-specific identity ("CampusGo") with larger touch targets and category filters (*All, Drinks, Food, Snacks*) would produce a superior kiosk experience.
* **Adaptation / Decision**:
  Approved the architecture, mandated strict server-side validation in PHP, and scheduled a future brand overhaul to match the team's visual identity.

---

### Stage 2: Database Schema & RESTful API Setup
* **Developer Prompt**:
  > *"Generate the MySQL database schema and PDO connection for CampusGo. Include products, transactions, and transaction_items with transactional integrity. Pre-seed the 6 products from the exam reference (Coffee ₱45, Sandwich ₱50, Soft Drink ₱35, Cookies ₱25, Bottled Water ₱20, Chocolate ₱25)."*
* **AI Output Summary**:
  Generated `database/schema.sql`, `config/db.php`, `api/get_products.php`, and `api/process_payment.php`.
* **Developer Evaluation**:
  The developers tested the generated SQL script directly with MySQL. They observed that client-side total calculations could be tampered with on a web kiosk, so server-side total verification was required.
* **Adaptation / Decision**:
  Developers enforced server-side total recalculation in `api/process_payment.php` using a database transaction (`beginTransaction` / `commit` / `rollBack`), ensuring `subtotal` and `total_amount` cannot be falsified by client payloads.

---

### Stage 3: Keypad Logic & Cash Validation
* **Developer Prompt**:
  > *"Implement the cash payment interface with a 3x4 touchscreen numeric keypad and quick cash amounts (Exact, ₱200, ₱500, ₱1,000). If the entered cash is less than the total, reject it, show a shortage warning with the exact formula, and set change to em-dash. When cash is sufficient, compute change dynamically."*
* **AI Output Summary**:
  Delivered keypad click handlers, live string formatting, and shortage calculation in `assets/js/kiosk.js`.
* **Developer Evaluation**:
  The initial AI implementation allowed multiple decimal points (e.g., `1..50`) if tapped repeatedly and did not clear the quick-amount active state when typing custom numbers on the keypad.
* **Adaptation / Decision**:
  Developers added decimal-point sanitization:
  ```javascript
  if (char === '.' && this.cashEnteredString.includes('.')) {
    return; // reject multiple decimal points
  }
  ```
  Additionally, developers ensured tapping any keypad number automatically deactivated the active quick-amount button highlighting (`clearQuickAmountActiveState()`).

---

### Stage 4: Visual Identity Redesign (QuestLearn Luminous Purple Theme)
* **Developer Prompt**:
  > *"We need a tweak for our color scheme. Follow the QuestLearn visual reference as a guide (deep violet obsidian tones, luminous purple gradients, soft lilac cards, glassmorphic header). Also update the header to be responsive and integrate our official circular kiosk logo from assets/img/campusgo-logo.png."*
* **AI Output Summary**:
  Updated `assets/css/style.css` design tokens:
  * `--header-bg-gradient`: `#180C2E` to `#251145` with glassmorphic backdrop filter.
  * `--purple-primary`: `#7C3AED` with hover `#6D28D9`.
  * `--page-bg`: Soft lilac ambient gradient `#F8F7FF` to `#F3F0FD`.
  * Added responsive styles for the top navigation bar.
* **Developer Evaluation**:
  On smaller tablet displays or narrow kiosk windows, the step pills (`1 Order`, `2 Review`, `3 Payment`, `4 Receipt`) were at risk of wrapping awkwardly onto multiple lines.
* **Adaptation / Decision**:
  Developers added an overflow container wrapper (`.steps-wrapper`) with `overflow-x: auto` and hidden scrollbar styling, ensuring steps remain on a single horizontal row with touch-scrolling on compact displays.

---

## 2. AI-Assisted Debugging Records

### Bug 1: Windows Command Line Curl JSON Encoding
* **Symptom**: When testing `api/process_payment.php` via Windows PowerShell curl commands, JSON double quotes were stripped, causing `Invalid JSON payload (400)`.
* **AI Suggestion**: Escape quotes with backslashes in PowerShell.
* **Developer Solution**: Created a dedicated PHP CLI test script (`test_api.php`) executing native `curl_init` with `json_encode($data)` to simulate real HTTP browser payloads accurately, confirming valid and insufficient cash responses before removing the test file.

### Bug 2: Foreign Key Constraint on Table Reset
* **Symptom**: Truncating `transactions` table caused a MySQL foreign key constraint failure due to child records in `transaction_items`.
* **AI Suggestion**: Delete rows manually.
* **Developer Solution**: Used `SET FOREIGN_KEY_CHECKS = 0; TRUNCATE transaction_items; TRUNCATE transactions; SET FOREIGN_KEY_CHECKS = 1;` to ensure clean, atomic resets for testing.

---

## 3. AI-Assisted Refactoring Milestones

1. **Modular Kiosk State Store**:
   Refactored fragmented functions into a centralized `Kiosk` object in `assets/js/kiosk.js`, managing `cart`, `currentStep`, `cashEnteredString`, and `lastTransaction`.
2. **Audio Synthesis Feedback**:
   Added a lightweight Web Audio API synthesizer (`Kiosk.playBeep()`) providing tactile click tones on touchscreen taps without requiring external MP3 sound files.
3. **80mm Thermal Receipt Print Stylesheet**:
   Refactored receipt DOM structure so `@media print` targets `#thermalReceiptSection` exclusively with `80mm` width and `5mm` margins, hiding UI headers and buttons during printing.

---

## 4. Developer Evaluation & Adaptation Summary

| Component | AI Initial Proposal | Developer Evaluation | Final Adapted Implementation |
| :--- | :--- | :--- | :--- |
| **Theme** | Navy & Orange (Sample UI) | Too generic; needed university identity | QuestLearn deep violet & luminous purple glass theme |
| **Header** | Fixed width flexbox | Breaks on mobile / vertical kiosk | Responsive `.header-inner` with scrollable `.steps-wrapper` |
| **Keypad** | Digits 0–9 only | Missing correction and clear controls | Added tactile `Clear`, `0`, and backspace (`⌫`) buttons |
| **Change Math** | Simple subtraction | Does not show math formula to user | Displays formula: `₱200.00 − ₱175.00 = ₱25.00` |
| **Security** | Client sends computed total | Vulnerable to client-side price tampering | Server recalculates totals from active DB product prices |
| **Audio** | No audio | Kiosk feels non-responsive | Synthesized Web Audio API feedback on all touch interactions |

---

## 5. Academic Integrity Statement
All AI-generated code and design suggestions were critically reviewed, modified, and verified by Team Roar members. The resulting codebase meets all specifications of the IT415 Acceptance Checklist and represents the intellectual and collaborative effort of the student development team.
