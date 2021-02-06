//Delete Wallet Modal Script
const deleteModal = document.querySelector('#deleteWallet');

deleteModal.addEventListener('show.bs.modal', function (event) {
// Button that triggered the modal
const deleteButton = event.relatedTarget;

// Extract info from data-bs-delete-payment-wallet attribute
const delete_payment_wallet= deleteButton.getAttribute('data-bs-delete-payment-wallet');
const delete_payment_wallet_json = JSON.parse(delete_payment_wallet);

const deleteModalBody = deleteModal.querySelector('.modal-body');
const deleteModalFooterInput = deleteModal.querySelector('.wallet_id');

deleteModalBody.textContent = `Are you sure you want to delete this wallet '${delete_payment_wallet_json['name']}'`;
deleteModalFooterInput.value = delete_payment_wallet_json['id']

});


// Add Money Paystack Modal Script
const addMoneyPaystackModal = document.querySelector('#addMoneyPaystack');

addMoneyPaystackModal.addEventListener('show.bs.modal', function (event) {
// Button that triggered the modal
const addMoneyButton = event.relatedTarget;

// Extract info from data-bs-delete-payment-wallet attribute
const payment_wallet_json = addMoneyButton.getAttribute('data-bs-add-money-to-payment-wallet-paystack');
const metaDataInput = addMoneyPaystackModal.querySelector('.metadata');

metaDataInput.value = payment_wallet_json;

})

//Add Money Flutterwave Modal Script
const addMoneyFlutterwaveModal = document.querySelector('#addMoneyFlutterwave');

addMoneyFlutterwaveModal.addEventListener('show.bs.modal', function (event) {
// Button that triggered the modal
const addMoneyButton = event.relatedTarget;
// Extract info from data-bs-delete-payment-wallet attribute
var payment_wallet_json= addMoneyButton.getAttribute('data-bs-add-money-to-payment-wallet-flutterwave')
//var payment_wallet_json = JSON.parse(payment_wallet)

var metaDataInput = addMoneyFlutterwaveModal.querySelector('.metadata')
metaDataInput.value = payment_wallet_json;
console.log(metaDataInput.value);

})


//Withdraw Money Modal Script
const withdrawMoneyModal = document.querySelector('#withdrawMoney');

withdrawMoneyModal.addEventListener('show.bs.modal', function (event) {
// Button that triggered the modal
var withdrawMoneybutton = event.relatedTarget;
// Extract info from data-bs-delete-payment-wallet attribute
var withdraw_money_payment_wallet= withdrawMoneybutton.getAttribute('data-bs-withdraw-money-from-payment-wallet');
var withdraw_money_payment_wallet_json = JSON.parse(withdraw_money_payment_wallet);

var  withdrawMoneyWalletId = withdrawMoneyModal.querySelector('.wallet-id');
withdrawMoneyWalletId.value = withdraw_money_payment_wallet_json["wallet_id"];

})
