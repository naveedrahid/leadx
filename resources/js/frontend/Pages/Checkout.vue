<template>
    <Head title="Check out" />
    <MasterLayout :loader="false">
        <!-- Progress Bar -->
        <section class="py-4 border-bottom">
            <div class="container">
                <div class="d-flex justify-content-center align-items-center gap-3 fw-semibold text-muted">
                    <div class="text-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i> Choose Plan
                    </div>
                    <div class="border-top border-warning flex-grow-1" style="max-width: 40px;"></div>
                    <div class="text-warning">2. Payment Info</div>
                    <div class="border-top flex-grow-1" style="max-width: 40px;"></div>
                    <div class="text-secondary">3. Instant Access</div>
                </div>
            </div>
        </section>

        <!-- Main Section -->
        <section class="py-5 bg-white">
            <div class="container">
                <div class="row g-5">
                    <!-- Left Column -->
                    <div class="col-md-7">
                        <div class="alert alert-warning rounded">
                            <strong>Special Offer Applied:</strong> Save 50% on your purchase today!
                        </div>

                        <h2 class="h5 mb-4">New Account Information</h2>
                        <form>
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="email" class="form-control" placeholder="Email Address *" />
                                </div>
                                <div class="col">
                                    <div class="input-group">
                    <span class="input-group-text bg-white">
                      <img src="https://flagcdn.com/w40/pk.png" alt="Flag" width="20" />
                    </span>
                                        <input type="text" class="form-control" placeholder="0301 2345678" />
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="First Name *" />
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Last Name *" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password *" />
                            </div>

                            <!-- Payment Method Selection -->
                            <h5 class="mt-5 mb-3">Select Payment Method</h5>
                            <div class="btn-group d-flex mb-4" role="group">
                                <button type="button" class="btn btn-outline-warning fw-bold w-50 active">
                                    Credit Card & More
                                </button>
                                <button type="button" class="btn btn-outline-secondary w-50">
                                    <i class="fab fa-paypal me-1"></i> PayPal
                                </button>
                            </div>

                            <!-- Card vs Bank Tab -->
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-card-tab" data-bs-toggle="pill" data-bs-target="#pills-card" type="button" role="tab">
                                        <i class="fas fa-credit-card me-1"></i> Card
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-bank-tab" data-bs-toggle="pill" data-bs-target="#pills-bank" type="button" role="tab">
                                        <i class="fas fa-university me-1"></i> Bank
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                <!-- Card Payment Form -->
                                <div class="tab-pane fade show active" id="pills-card" role="tabpanel">
                                    <div class="bg-light p-3 rounded border">

                                        <div class="mb-3 text-success fw-semibold">
                                            <i class="fas fa-lock me-1"></i> Secure, 1-click checkout with Link
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Card Number</label>
                                            <input type="text" class="form-control" placeholder="1234 1234 1234 1234" />
                                            <div class="mt-2">
                                                <img src="https://img.icons8.com/color/32/000000/mastercard-logo.png" />
                                                <img src="https://img.icons8.com/color/32/000000/visa.png" />
                                                <img src="https://img.icons8.com/color/32/000000/amex.png" />
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="form-label fw-bold">Expiry Date</label>
                                                <input type="text" class="form-control" placeholder="MM / YY" />
                                            </div>
                                            <div class="col">
                                                <label class="form-label fw-bold">Security Code</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="CVC" />
                                                    <span class="input-group-text bg-white">
              <i class="fas fa-credit-card"></i>
            </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Billing Country <span class="text-danger">*</span></label>
                                            <select class="form-select">
                                                <option selected>Pakistan</option>
                                                <option>USA</option>
                                                <option>UK</option>
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center gap-3 mt-4">
                                            <img src="https://www.nortonlifelock.com/themes/custom/norton/logo.svg" height="30" />
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/6/69/PayPal_2014_logo.png" height="30" />
                                            <img src="https://seeklogo.com/images/M/McAfee-logo-27D9D25D3E-seeklogo.com.png" height="30" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank Payment Placeholder -->
                                <div class="tab-pane fade" id="pills-bank" role="tabpanel">
                                    <div class="bg-light p-3 rounded border">
                                        <p class="text-muted">Bank payment method coming soon.</p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-5">
                        <div class="bg-light p-4 rounded shadow-sm">
                            <h2 class="h5 mb-3">Your Order</h2>

                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>WPForms Pro</span>
                                <span>$399.00</span>
                            </div>

                            <div class="d-flex justify-content-between border-bottom py-2 text-primary">
                <span>
                  Discount Applied
                  <span class="badge bg-primary ms-2">50% Off</span>
                </span>
                                <span class="text-danger">- $199.50</span>
                            </div>

                            <div class="d-flex justify-content-between fw-bold fs-5 py-3">
                                <span>Total:</span>
                                <span>$199.50</span>
                            </div>

                            <blockquote class="blockquote mt-4">
                                <p class="mb-0 fst-italic text-secondary">
                                    "The interface is straightforward, the creation process simple, and the range of options and templates is all one could wish for. Impossible to criticise."
                                </p>
                                <footer class="blockquote-footer mt-2 fw-semibold text-dark">
                                    Harveyyates <cite class="text-success ms-2">✔ VERIFIED CUSTOMER</cite>
                                </footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MasterLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import MasterLayout from '@/frontend/Layouts/MasterLayout.vue';

export default {
    components: {
        Head,
        MasterLayout,
    },
};
</script>
