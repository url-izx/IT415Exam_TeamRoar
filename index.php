<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>CampusGo — Self-Service Campus POS Kiosk</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/img/campusgo-logo.png">

  <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Custom Kiosk Theme CSS (Purple / Violet QuestLearn Theme) -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <!-- KIOSK RESPONSIVE TOP HEADER -->
  <header class="kiosk-header">
    <div class="header-inner">
      <!-- Left: Brand Logo & Title -->
      <div class="brand-wrapper">
        <img src="assets/img/campusgo-logo.png" alt="CampusGo Logo" class="brand-logo-img">
        <div class="brand-info">
          <div class="d-flex align-items-center gap-2">
            <h1 class="brand-title">CampusGo</h1>
            <span class="brand-badge-pill">Self-Service</span>
          </div>
          <p class="brand-subtitle">Smart Campus Kiosk Terminal</p>
        </div>
      </div>

      <!-- Right: Step Indicators Bar (Horizontally scrollable on small screens) -->
      <div class="steps-wrapper">
        <nav class="steps-container" aria-label="Order progress">
          <div class="step-pill active" id="stepPill1">1 Order</div>
          <div class="step-pill" id="stepPill2">2 Review</div>
          <div class="step-pill" id="stepPill3">3 Payment</div>
          <div class="step-pill" id="stepPill4">4 Receipt</div>
        </nav>
      </div>
    </div>
  </header>

  <!-- MAIN KIOSK VIEWPORTS -->
  <main class="kiosk-main">

    <!-- SCREEN 1: ORDER / CATALOG -->
    <div class="kiosk-screen-view" id="screenOrder">
      <div class="row g-4">
        <!-- Catalog Column -->
        <div class="col-lg-8">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h2 class="section-title m-0">Tap a product to add it</h2>
            <!-- Category Filter Pills -->
            <div class="category-filter-group m-0" id="categoryFilters">
              <!-- Rendered dynamically -->
            </div>
          </div>

          <!-- Product Cards Grid -->
          <div class="products-grid" id="productsGrid">
            <!-- Rendered dynamically -->
          </div>
        </div>

        <!-- Cart Sidebar Column -->
        <div class="col-lg-4">
          <div class="cart-card">
            <div class="cart-header">
              <h3 class="cart-title">Your Order</h3>
              <span class="cart-item-badge" id="cartItemCountBadge">0 items</span>
            </div>

            <!-- Items List -->
            <div class="cart-items-list" id="cartItemsList">
              <!-- Rendered dynamically -->
            </div>

            <!-- Cart Total & Proceed Button -->
            <div class="cart-total-section">
              <div class="cart-total-row">
                <span class="cart-total-label">Total</span>
                <span class="cart-total-amount" id="cartTotalAmount">₱0.00</span>
              </div>
              <button class="btn-kiosk-primary" onclick="Kiosk.goToStep(2)" id="btnProceedToReview">
                Proceed to Payment →
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- SCREEN 2: REVIEW ORDER -->
    <div class="kiosk-screen-view d-none" id="screenReview">
      <div class="review-card">
        <div class="review-header">
          <h2>Review your order</h2>
          <p>Check your items before paying. Tap Back to make changes — your items stay in the cart.</p>
        </div>

        <div class="review-table-container">
          <table class="review-table">
            <thead>
              <tr>
                <th>PRODUCT</th>
                <th class="text-center">QUANTITY</th>
                <th>UNIT PRICE</th>
                <th class="text-end">SUBTOTAL</th>
              </tr>
            </thead>
            <tbody id="reviewTableBody">
              <!-- Rendered dynamically -->
            </tbody>
          </table>
        </div>

        <div class="review-total-banner">
          <div class="total-info">
            <h4>Total Amount</h4>
            <span id="reviewTotalItemsCount">0 items</span>
          </div>
          <div class="total-price" id="reviewTotalAmount">₱0.00</div>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
          <button class="btn-kiosk-secondary px-5" onclick="Kiosk.goToStep(1)">
            ← Back
          </button>
          <button class="btn-kiosk-primary px-5" style="max-width: 440px;" onclick="Kiosk.goToStep(3)">
            Continue to Payment →
          </button>
        </div>
      </div>
    </div>

    <!-- SCREEN 3: PAYMENT METHOD SELECTION -->
    <div class="kiosk-screen-view d-none" id="screenPaymentSelect">
      <div class="max-w-1000 mx-auto">
        <div class="payment-select-header flex-wrap gap-3">
          <div>
            <h2 class="section-title m-0">How would you like to pay?</h2>
            <p class="text-muted fs-5 mt-1 mb-0">Tap one of the options below.</p>
          </div>
          <div class="due-badge-box">
            <div class="due-badge-label">AMOUNT DUE</div>
            <div class="due-badge-amount" id="paymentDueAmountText">₱0.00</div>
          </div>
        </div>

        <!-- 3 Big Payment Cards -->
        <div class="payment-methods-grid">
          <!-- Cash Card -->
          <div class="payment-method-card" onclick="Kiosk.choosePaymentMethod('Cash')">
            <div class="pay-icon-circle pay-icon-cash">💵</div>
            <h3 class="pay-method-title">Cash</h3>
            <p class="pay-method-desc">Enter the amount you are paying. Change is computed for you.</p>
          </div>

          <!-- QR Payment Card -->
          <div class="payment-method-card" onclick="Kiosk.choosePaymentMethod('QR Payment')">
            <div class="pay-icon-circle pay-icon-qr">▦</div>
            <h3 class="pay-method-title">QR Payment</h3>
            <p class="pay-method-desc">Scan with a supported e-wallet or banking app.</p>
          </div>

          <!-- Credit / Debit Card -->
          <div class="payment-method-card" onclick="Kiosk.choosePaymentMethod('Credit / Debit Card')">
            <div class="pay-icon-circle pay-icon-card">💳</div>
            <h3 class="pay-method-title">Credit / Debit Card</h3>
            <p class="pay-method-desc">Tap, insert, or swipe your card at the reader.</p>
          </div>
        </div>

        <div>
          <button class="btn-kiosk-secondary px-4" onclick="Kiosk.goToStep(1)">
            ← Back to Order
          </button>
        </div>
      </div>
    </div>

    <!-- SCREEN 3.1: CASH PAYMENT (KEYPAD & CHANGE COMPUTATION) -->
    <div class="kiosk-screen-view d-none" id="screenCashPayment">
      <div class="cash-layout">
        <!-- Left: Cash Computation Column -->
        <div class="cash-info-card">
          <div class="d-flex align-items-center gap-2 mb-4">
            <span class="fs-3">💵</span>
            <h3 class="fw-bold m-0" style="color: var(--text-dark);">Cash Payment</h3>
          </div>

          <!-- Total Amount Box -->
          <div class="total-due-display">
            <span>Total amount</span>
            <h3 id="cashTotalDueAmount">₱0.00</h3>
          </div>

          <!-- Amount Paid Display Box -->
          <label class="fw-bold text-muted mb-2">Amount paid</label>
          <div class="paid-input-box" id="cashPaidDisplayBox">₱0.00</div>

          <!-- Insufficient Payment Alert Banner -->
          <div class="insufficient-alert d-none" id="insufficientAlert">
            <div class="alert-icon">⚠️</div>
            <div>
              <strong>Insufficient payment.</strong>
              <p id="insufficientMsg">Please enter at least the total amount.</p>
            </div>
          </div>

          <!-- Quick Cash Amounts -->
          <div class="quick-amounts-label">Quick amounts</div>
          <div class="quick-amounts-grid">
            <button class="btn-quick-amt" id="quick_btn_exact" onclick="Kiosk.setQuickCash('exact')">Exact</button>
            <button class="btn-quick-amt" id="quick_btn_200" onclick="Kiosk.setQuickCash('200', 200)">₱200</button>
            <button class="btn-quick-amt" id="quick_btn_500" onclick="Kiosk.setQuickCash('500', 500)">₱500</button>
            <button class="btn-quick-amt" id="quick_btn_1000" onclick="Kiosk.setQuickCash('1000', 1000)">₱1,000</button>
          </div>

          <!-- Change Box -->
          <div class="change-display-card" id="changeDisplayCard">
            <div class="change-label-wrap">
              <span>Change</span>
              <small id="changeFormulaText">Computed change</small>
            </div>
            <h3 class="change-amount-text" id="changeAmountValue">₱0.00</h3>
          </div>
        </div>

        <!-- Right: Touchscreen Numeric Keypad -->
        <div class="cash-keypad-card d-flex flex-column justify-content-between">
          <div class="keypad-grid">
            <button class="btn-keypad" onclick="Kiosk.keypadInput('1')">1</button>
            <button class="btn-keypad" onclick="Kiosk.keypadInput('2')">2</button>
            <button class="btn-keypad" onclick="Kiosk.keypadInput('3')">3</button>

            <button class="btn-keypad" onclick="Kiosk.keypadInput('4')">4</button>
            <button class="btn-keypad" onclick="Kiosk.keypadInput('5')">5</button>
            <button class="btn-keypad" onclick="Kiosk.keypadInput('6')">6</button>

            <button class="btn-keypad" onclick="Kiosk.keypadInput('7')">7</button>
            <button class="btn-keypad" onclick="Kiosk.keypadInput('8')">8</button>
            <button class="btn-keypad" onclick="Kiosk.keypadInput('9')">9</button>

            <button class="btn-keypad special" onclick="Kiosk.keypadInput('clear')">Clear</button>
            <button class="btn-keypad" onclick="Kiosk.keypadInput('0')">0</button>
            <button class="btn-keypad special" onclick="Kiosk.keypadInput('backspace')">⌫</button>
          </div>

          <div class="d-flex flex-column gap-3">
            <button class="btn-kiosk-primary" onclick="Kiosk.submitCashPayment()">
              Pay Now
            </button>
            <button class="btn-kiosk-secondary" onclick="Kiosk.goToStep(3)">
              ← Change payment method
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- SCREEN 3.2: QR PAYMENT (SIMULATED) -->
    <div class="kiosk-screen-view d-none" id="screenQRPayment">
      <div class="qr-card-layout">
        <!-- Left: QR Code Mockup -->
        <div class="visual-mockup-card">
          <div class="qr-placeholder-box">
            <!-- Simulated Crisp SVG QR Code -->
            <svg class="qr-code-graphic" viewBox="0 0 100 100" fill="#180C2E">
              <!-- Corner Top-Left -->
              <rect x="5" y="5" width="28" height="28" fill="none" stroke="#7C3AED" stroke-width="6" rx="2" />
              <rect x="13" y="13" width="12" height="12" fill="#7C3AED" />
              <!-- Corner Top-Right -->
              <rect x="67" y="5" width="28" height="28" fill="none" stroke="#7C3AED" stroke-width="6" rx="2" />
              <rect x="75" y="13" width="12" height="12" fill="#7C3AED" />
              <!-- Corner Bottom-Left -->
              <rect x="5" y="67" width="28" height="28" fill="none" stroke="#7C3AED" stroke-width="6" rx="2" />
              <rect x="13" y="75" width="12" height="12" fill="#7C3AED" />
              <!-- Data Patterns -->
              <rect x="38" y="10" width="8" height="8" />
              <rect x="50" y="10" width="8" height="8" />
              <rect x="42" y="24" width="16" height="8" />
              <rect x="10" y="42" width="12" height="8" />
              <rect x="28" y="42" width="8" height="8" />
              <rect x="42" y="42" width="16" height="16" />
              <rect x="64" y="42" width="8" height="16" />
              <rect x="78" y="42" width="14" height="8" />
              <rect x="78" y="56" width="8" height="16" />
              <rect x="38" y="66" width="8" height="8" />
              <rect x="52" y="66" width="18" height="8" />
              <rect x="38" y="80" width="18" height="12" />
              <rect x="64" y="80" width="12" height="12" />
              <rect x="82" y="80" width="10" height="10" />
            </svg>
            <small class="text-muted fw-bold mt-2" style="font-family: var(--font-receipt); font-size: 0.75rem;">QR CODE placeholder</small>
          </div>
          <div class="ref-code-text" id="qrReferenceCodeText">Ref: QR-TXN-2026-00126</div>
        </div>

        <!-- Right: Instructions & Confirmation -->
        <div class="visual-mockup-card align-items-start text-start">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="fs-3" style="color: var(--purple-primary);">▦</span>
            <h3 class="fw-bold m-0" style="color: var(--text-dark);">QR Payment</h3>
          </div>

          <div class="w-100 p-3 rounded-4 mb-3" style="background: #FAF5FF; border: 1.5px solid #EDE9FE;">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold text-muted fs-5">Amount to pay</span>
              <span class="fw-black fs-2" style="color: var(--purple-primary) !important;" id="qrDueAmountText">₱0.00</span>
            </div>
          </div>

          <ol class="instructions-list">
            <li>
              <span class="step-num-badge">1</span>
              <span>Scan the QR code using your supported payment application.</span>
            </li>
            <li>
              <span class="step-num-badge">2</span>
              <span>Check that the amount matches and approve it in your app.</span>
            </li>
            <li>
              <span class="step-num-badge">3</span>
              <span>Tap Confirm Payment below.</span>
            </li>
          </ol>

          <div class="d-flex w-100 gap-3 mt-4">
            <button class="btn-kiosk-secondary px-4" onclick="Kiosk.goToStep(3)">
              ← Back
            </button>
            <button class="btn-kiosk-primary" onclick="Kiosk.confirmQRPayment()">
              ✓ Confirm Payment
            </button>
          </div>

          <p class="text-muted small mt-3 mb-0">
            Simulated payment — the amount paid will equal the total, with ₱0.00 change.
          </p>
        </div>
      </div>
    </div>

    <!-- SCREEN 3.3: CREDIT / DEBIT CARD PAYMENT (SIMULATED) -->
    <div class="kiosk-screen-view d-none" id="screenCardPayment">
      <div class="qr-card-layout">
        <!-- Left: Card Terminal Illustration -->
        <div class="visual-mockup-card">
          <div class="pos-terminal-mockup">
            <div class="terminal-screen">
              <span>INSERT / TAP</span>
              <small class="mt-1" style="color: #A78BFA;">READY</small>
            </div>
            <div class="contactless-waves">)))</div>
            <!-- Tilted Purple Card -->
            <div class="floating-card-mockup">
              <div class="card-chip"></div>
              <small style="font-family: var(--font-receipt); font-size: 0.65rem;">•••• 4821</small>
            </div>
          </div>
        </div>

        <!-- Right: Instructions & Terminal Progress -->
        <div class="visual-mockup-card align-items-start text-start">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="fs-3" style="color: var(--purple-primary);">💳</span>
            <h3 class="fw-bold m-0" style="color: var(--text-dark);">Credit / Debit Card</h3>
          </div>

          <div class="w-100 p-3 rounded-4 mb-3" style="background: #FAF5FF; border: 1.5px solid #EDE9FE;">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold text-muted fs-5">Amount due</span>
              <span class="fw-black fs-2" style="color: var(--purple-primary);" id="cardDueAmountText">₱0.00</span>
            </div>
          </div>

          <p class="fw-bold text-secondary fs-5 mb-3">
            Please tap, insert, or swipe your card.
          </p>

          <!-- Processing State Card -->
          <div class="payment-processing-widget w-100">
            <div class="processing-header">
              <span>↻</span>
              <span>Processing payment...</span>
            </div>
            <div class="card-progress-bar">
              <div class="card-progress-fill" id="cardProgressBarFill"></div>
            </div>
            <small class="text-muted fw-semibold">
              Do not remove your card until the payment is complete.
            </small>
          </div>

          <div class="d-flex w-100 gap-3 mt-4">
            <button class="btn-kiosk-secondary px-4" onclick="Kiosk.goToStep(3)">
              ← Back
            </button>
            <button class="btn-kiosk-primary" id="btnProcessCard" onclick="Kiosk.processCardPayment()">
              Process Payment
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- SCREEN 4: PAYMENT SUCCESSFUL -->
    <div class="kiosk-screen-view d-none" id="screenSuccess">
      <div class="success-modal-card">
        <div class="success-check-circle">✓</div>
        <h2 class="success-title">Payment Successful</h2>
        <p class="success-subtitle">Transaction completed successfully. Thank you!</p>

        <table class="success-details-table">
          <tbody>
            <tr>
              <td>Transaction No.</td>
              <td id="successTxnNo">TXN-2026-00125</td>
            </tr>
            <tr>
              <td>Payment method</td>
              <td id="successMethod">Cash</td>
            </tr>
            <tr>
              <td>Transaction amount</td>
              <td id="successAmount">₱0.00</td>
            </tr>
            <tr>
              <td>Amount paid</td>
              <td id="successPaid">₱0.00</td>
            </tr>
            <tr>
              <td>Change</td>
              <td class="change-val" id="successChange">₱0.00</td>
            </tr>
          </tbody>
        </table>

        <button class="btn-kiosk-primary" onclick="Kiosk.goToStep(5)">
          📄 View Receipt
        </button>
      </div>
    </div>

    <!-- SCREEN 5: DIGITAL RECEIPT -->
    <div class="kiosk-screen-view d-none" id="screenReceipt">
      <div class="receipt-layout">
        <!-- Left: Monospace Thermal Paper Receipt -->
        <div class="thermal-receipt-paper" id="thermalReceiptSection">
          <div class="receipt-header-center">
            <div class="receipt-store-title">CAMPUSGO POS</div>
            <div class="receipt-store-sub">Self-Service Kiosk · Official Digital Receipt</div>
          </div>

          <div class="receipt-meta">
            <span>Transaction No.</span>
            <span id="rcptTxnNo">TXN-2026-00125</span>
          </div>
          <div class="receipt-meta">
            <span>Date</span>
            <span id="rcptDate">October 6, 2026 · 10:42 AM</span>
          </div>

          <div class="receipt-divider-dashed"></div>

          <div class="receipt-table-header">
            <span>ITEM</span>
            <span>SUBTOTAL</span>
          </div>

          <!-- Line items -->
          <div id="rcptItemsContainer">
            <!-- Rendered dynamically -->
          </div>

          <div class="receipt-divider-dashed"></div>

          <div class="receipt-total-row">
            <span class="receipt-total-label">TOTAL</span>
            <span class="receipt-total-val" id="rcptTotal">₱0.00</span>
          </div>

          <div class="receipt-summary-line mt-3">
            <span>Payment method</span>
            <span id="rcptMethod">Cash</span>
          </div>
          <div class="receipt-summary-line">
            <span>Amount paid</span>
            <span id="rcptPaid">₱0.00</span>
          </div>
          <div class="receipt-summary-line">
            <span>Change</span>
            <span id="rcptChange">₱0.00</span>
          </div>
          <div class="receipt-summary-line">
            <span>Status</span>
            <span class="receipt-status-success">Payment Successful</span>
          </div>

          <div class="receipt-divider-dashed"></div>

          <div class="receipt-footer-center">
            Thank you for your purchase!
          </div>
        </div>

        <!-- Right: Actions & New Transaction Trigger -->
        <div class="receipt-action-side">
          <h2>Your receipt</h2>
          <p>
            Keep this for your records. Tap New Transaction when you are done — your order and payment details will be cleared.
          </p>

          <div class="receipt-btns-wrap">
            <button class="btn-kiosk-primary" onclick="Kiosk.startNewTransaction()">
              + New Transaction
            </button>
            <button class="btn-kiosk-secondary" onclick="Kiosk.printReceipt()">
              🖨 Print Receipt
            </button>
          </div>

          <small class="text-muted d-block fw-semibold">
            Printing is optional — the digital receipt is your proof of payment.
          </small>
        </div>
      </div>
    </div>

  </main>

  <!-- FLOATING BOTTOM TOAST NOTIFICATION -->
  <div class="kiosk-toast" id="kioskToast">
    <span class="toast-check">✓</span>
    <span id="toastMessageText">Product added</span>
  </div>

  <!-- Bootstrap 5 Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>

  <!-- Kiosk App JS -->
  <script src="assets/js/kiosk.js"></script>
</body>
</html>
