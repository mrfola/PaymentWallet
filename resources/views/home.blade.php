@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-2 ">
        <div class="col-md-10 bg-white p-4">

      @if(session('wallet_message'))
      <div class="alert alert-primary" role="alert">{{session('wallet_message')}}</div>
      @endif

      @if(session('wallet_error'))
      <div class="alert alert-danger" role="alert">{{session('wallet_error')}}</div>
      @endif

            <div class="my-2" style="display:flex; justify-content:flex-end;">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWallet" type="button" style="color:white;">Create Wallet</button>
            </div>   
            {{ 'RAVE PUBLIC KEY: '.config('rave.publicKey')}}
            @isset($payment_wallets)
                <div style="display:flex; flex-wrap:nowrap;">
                    @foreach($payment_wallets as $payment_wallet)  
                    <div class="card m-2">
                        <div class="card-header">{{$payment_wallet['name']}}
                            <button class="btn btn-danger" type="button" style="color:white; float:right;" data-bs-toggle="modal" data-bs-target="#deleteWallet" data-bs-delete-payment-wallet='{"name": "{{$payment_wallet['name']}}", "id": "{{$payment_wallet['id']}}" }' >Delete</button>
                        </div>
                        <div class="card-body">
                            <p><b>Description: </b> {{$payment_wallet["description"]}}</p>
                            <p><b>Wallet Balance: </b>{{$payment_wallet["account_balance"]}}</p>
                            <div  style="float:right;">

                            <button class="btn btn-primary" style="color:white;" data-bs-toggle="modal" data-bs-target="#withdrawMoney" type="button" data-bs-withdraw-money-from-payment-wallet="{{json_encode($array = ['wallet_id' => $payment_wallet['id']])}}">Withdraw</button>
                            <a class="btn btn-success" style="color:white;"  data-bs-toggle="collapse" href="{{'#collapseExample'.$loop->iteration}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                Top Up
                            </a>

                            <div class="collapse p-2 my-2" id="{{'collapseExample'.$loop->iteration}}" style="background:#f2f2f2;">
                                <button class="btn btn-success my-2" type="button" style="color:white;" data-bs-toggle="modal" data-bs-target="#addMoneyPaystack" data-bs-add-money-to-payment-wallet-paystack="{{json_encode($array = ['wallet_id' => $payment_wallet['id']])}}">Pay With Paystack</button> <br>
                                <button class="btn btn-success my-2" type="button" style="color:white;" data-bs-toggle="modal" data-bs-target="#addMoneyFlutterwave" data-bs-add-money-to-payment-wallet-flutterwave="{{json_encode(array(array('metaname' => 'wallet_id', 'metavalue' => $payment_wallet['id'])))}} ">Pay With Flutterwave</button>                                
                            </div>
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
                    <input name="amount" class="form-control" value="1000"> {{-- required in kobo --}}
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
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input name="amount" class="form-control" value="1000">
                    </div>
                    <input type="hidden" name="payment_method" value="both" />
                    <input type="hidden" name="email" value="{{Auth()->user()->email}}"> {{-- required --}}
                    <input type="hidden" name="orderID" value="345">
                    <input type="hidden" name="country" value="NG" />
                    <input type="hidden" name="currency" value="NGN">
                    <input type="hidden" class="metadata" name="metadata" value="{{ json_encode($array = ['key_name' => 'value',]) }}" >
                    <input type="hidden" name="firstname" value="Oluwole" />
                    <input type="hidden" name="lastname" value="Adebiyi" />
                    <input type="hidden" name="phonenumber" value="09000000000" /> 

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" href="#" style="color:white;">Add Money</button>
                </div>
            </form>
        </div>
    </div>
</div>


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

<script type="text/javascript" src="{{ asset('js/home.js') }}" ></script>
