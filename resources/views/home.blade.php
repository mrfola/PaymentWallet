@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-2 ">
        <div class="col-md-10 bg-white p-4">

      @if(session('message'))
      <div class="alert alert-primary" role="alert">{{session('message')}}</div>
      @endif

      @if(session('error'))
      <div class="alert alert-danger" role="alert">{{session('error')}}</div>
      @endif

            <div class="my-2" style="display:flex; justify-content:flex-end;">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWallet" type="button" style="color:white;">Create Wallet</button>
            </div>   

            @isset($payment_wallets)
                <div class="display:flex; flex-wrap:nowrap;">
                    @foreach($payment_wallets as $payment_wallet)  
                    <div class="card my-2">
                        <div class="card-header">{{$payment_wallet['name']}}
                            <button class="btn btn-danger" type="button" style="color:white; float:right;" data-bs-toggle="modal" data-bs-target="#deleteWallet" data-bs-delete-payment-wallet='{"name": "{{$payment_wallet['name']}}", "id": "{{$payment_wallet['id']}}" }' >Delete</button>
                        </div>
                        <div class="card-body">
                            <p><b>Description: </b> {{$payment_wallet["description"]}}</p>
                            <p><b>Wallet Balance: </b>{{$payment_wallet["account_balance"]}}</p>
                            <div  style="float:right;">
                            <button class="btn btn-success" type="button" style="color:white;" data-bs-toggle="modal" data-bs-target="#addMoneyPaystack" data-bs-add-money-to-payment-wallet-paystack="{{json_encode($array = ['wallet_id' => $payment_wallet['id']])}}">Add Money With Paystack</button>
                            <button class="btn btn-success" type="button" style="color:white;" data-bs-toggle="modal" data-bs-target="#addMoneyFlutterwave" data-bs-add-money-to-payment-wallet-flutterwave="{{json_encode(array(array('metaname' => 'wallet_id', 'metavalue' => $payment_wallet['id'])))}} ">Add Money With Flutterwave</button>
                            <button class="btn btn-primary" style="color:white;" data-bs-toggle="modal" data-bs-target="#withdrawMoney" type="button" data-bs-withdraw-money-from-payment-wallet="{{json_encode($array = ['wallet_id' => $payment_wallet['id']])}}">Withdraw</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endisset
        </div>
    </div>

</div>
@endsection


<!-- Create Wallet Modal -->
<div class="modal fade" id="createWallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Create A New Wallet</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form method="POST" action="/wallet">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="name" class="form-label">Wallet Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Grocery Wallet">
                    </div>

                    <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <label for="walletCurrencies" class="form-label">Wallet Currency</label>
                    <input class="form-control" list="datalistOptions" id="walletCurrencies" name="currency" placeholder="Search for a wallet currency (e.g Nigerian Naira)...">
                    <datalist id="datalistOptions">
                    <option value="Nigerian Naira">
                    <option value="US Dollars">
                    <option value="Canadian Dollars">
                    <option value="Pound Sterling">
                    <option value="Yuan">
                    </datalist>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" href="#" style="color:white;">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Wallet Modal -->
<div class="modal fade" id="deleteWallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure you want to delete this wallet?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this wallet?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="/wallet">
                @csrf
                @method('DELETE')
                <input type="hidden" class="wallet_id" name="wallet_id" value="0">
                <button type="submit" class="btn btn-danger" style="color:white;">Delete Wallet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Wallet Modal Script-->
<script type="text/javascript">
    var deleteModal = document.getElementById('deleteWallet')
   deleteModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var deleteButton = event.relatedTarget
    // Extract info from data-bs-delete-payment-wallet attribute
    var delete_payment_wallet= deleteButton.getAttribute('data-bs-delete-payment-wallet')
    console.log(delete_payment_wallet)
    var delete_payment_wallet_json = JSON.parse(delete_payment_wallet)

    var deleteModalBody = deleteModal.querySelector('.modal-body')
    var deleteModalFooterInput = deleteModal.querySelector('.wallet_id')
    deleteModalBody.textContent = 'Are you sure you want to delete this wallet "' + delete_payment_wallet_json['name'] + '"'
    deleteModalFooterInput.value = delete_payment_wallet_json['id']
    console.log(deleteModalFooterInput.value)

    })
</script>

<!-- Add Money Paystack Modal -->
<div class="modal fade" id="addMoneyPaystack" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">How much do you want to add?</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form method="POST" action="{{route('pay-paystack')}}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input name="amount" class="form-control" value="800"> {{-- required in kobo --}}
                    </div>
                    <input type="hidden" name="email" value="{{Auth()->user()->email}}"> {{-- required --}}
                    <input type="hidden" name="orderID" value="345">
                    <input type="hidden" name="currency" value="NGN">
                    <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}"> {{-- required --}}        
                    <input type="hidden" class="metadata" name="metadata" value="{{ json_encode($array = ['key_name' => 'value',]) }}" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" href="#" style="color:white;">Add Money</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Money Paystack Modal Script-->
<script type="text/javascript">
    var addMoneyPaystackModal = document.getElementById('addMoneyPaystack')
    addMoneyPaystackModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var addMoneyPaystackbutton = event.relatedTarget
    // Extract info from data-bs-delete-payment-wallet attribute
    var add_money_paystack_payment_wallet_json= addMoneyPaystackbutton.getAttribute('data-bs-add-money-to-payment-wallet-paystack')
    //var payment_wallet_json = JSON.parse(payment_wallet)

    var addMoneyPaystackMetaDataInput = addMoneyPaystackModal.querySelector('.metadata')
    addMoneyPaystackMetaDataInput.value = add_money_paystack_payment_wallet_json
    console.log(addMoneyPaystackMetaDataInput.value)

    })
</script>

<!-- Add Money Flutterwave Modal -->
<div class="modal fade" id="addMoneyFlutterwave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">How much do you want to add?</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form method="POST" action="{{route('pay-rave')}}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input name="amount" class="form-control" value="800"> {{-- required in kobo --}}
                    </div>
                    <input type="hidden" name="email" value="{{Auth()->user()->email}}"> {{-- required --}}
                    <input type="hidden" name="orderID" value="345">
                    <input type="hidden" name="country" value="NG" />
                    <input type="hidden" name="currency" value="NGN">
                    <input type="hidden" class="metadata" name="metadata" value="{{ json_encode($array = ['key_name' => 'value',]) }}" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" href="#" style="color:white;">Add Money</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Money Flutterwave Modal Script-->
<script type="text/javascript">
    var addMoneyFlutterwaveModal = document.getElementById('addMoneyFlutterwave')
    addMoneyFlutterwaveModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var addMoneyFlutterwavebutton = event.relatedTarget
    // Extract info from data-bs-delete-payment-wallet attribute
    var add_money_flutterwave_payment_wallet_json= addMoneyFlutterwavebutton.getAttribute('data-bs-add-money-to-payment-wallet-flutterwave')
    //var payment_wallet_json = JSON.parse(payment_wallet)

    var addMoneyFlutterwaveMetaDataInput = addMoneyFlutterwaveModal.querySelector('.metadata')
    addMoneyFlutterwaveMetaDataInput.value = add_money_flutterwave_payment_wallet_json
    console.log(addMoneyFlutterwaveMetaDataInput.value)

    })
</script>

<!-- Withdraw Money Modal -->
<div class="modal fade" id="withdrawMoney" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">How much do you want to withdraw?</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form method="POST" action="/withdraw">
                @csrf
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="1,000">
                        <input type="hidden" class="wallet-id" name="wallet_id" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" href="#" style="color:white;">Withdraw</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Withdraw Money Modal Script-->
<script type="text/javascript">
    var withdrawMoneyModal = document.getElementById('withdrawMoney')
    withdrawMoneyModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var withdrawMoneybutton = event.relatedTarget
    // Extract info from data-bs-delete-payment-wallet attribute
    var withdraw_money_payment_wallet= withdrawMoneybutton.getAttribute('data-bs-withdraw-money-from-payment-wallet')
    var withdraw_money_payment_wallet_json = JSON.parse(withdraw_money_payment_wallet)

    var  withdrawMoneyWalletId = withdrawMoneyModal.querySelector('.wallet-id')
    withdrawMoneyWalletId.value = withdraw_money_payment_wallet_json["wallet_id"]
    console.log( withdrawMoneyWalletId.value)

    })
</script>