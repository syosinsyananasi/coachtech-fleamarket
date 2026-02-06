document.addEventListener('DOMContentLoaded', function() {
    var paymentSelect = document.getElementById('payment_method');
    var selectedPayment = document.getElementById('selected-payment');
    var purchaseForm = document.getElementById('purchase-form');

    if (paymentSelect && selectedPayment) {
        paymentSelect.addEventListener('change', function() {
            selectedPayment.textContent = this.value || '未選択';
        });
    }

    if (purchaseForm && paymentSelect) {
        purchaseForm.addEventListener('submit', function() {
            var paymentMethod = paymentSelect.value;
            if (paymentMethod === 'コンビニ支払い') {
                this.target = '_blank';
            } else {
                this.target = '_self';
            }
        });
    }
});
