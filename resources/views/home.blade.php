@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-2 ">
        <div class="col-md-10 bg-white p-4">

            @if(session('message'))
                <div class="alert alert-primary" role="alert">{{session('message')}}</div>
            @endif

            <div class="my-2" style="display:flex; justify-content:flex-end;">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWallet" type="button" style="color:white;">Create Wallet</button>
            </div>   

            @isset($payment_wallets)
                <div class="display:flex; flex-wrap:nowrap;">
                    @foreach($payment_wallets as $payment_wallet)  
                    <div class="card my-2">
                        <div class="card-header">{{$payment_wallet['name']}}
                        <button class="btn btn-danger" type="button" style="color:white; float:right;" data-bs-toggle="modal" data-bs-target="#deleteWallet">Delete</button></div>
                        <div class="card-body">
                            <p><b>Description:</b> {{$payment_wallet["description"]}}</p>
                            <p><b>Wallet Balance:</b>{{$payment_wallet["balance"]}}</p>
                            <div  style="float:right;">
                            <button class="btn btn-success" type="button" style="color:white;" data-bs-toggle="modal" data-bs-target="#addMoney">Add Money</button>
                            <button class="btn btn-primary" style="color:white;" data-bs-toggle="modal" data-bs-target="#withdrawMoney" type="button" >Withdraw</button>
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
                <p>Are you sure you want to delete this wallet "{{isset($payment_wallets[$payment_wallet]) ? $payment_wallet['name'] : ''}}"?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="/wallet/{{isset($payment_wallet['id']) ? $payment_wallet['id'] : ''}}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" href="#" style="color:white;">Delete Wallet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Money Modal -->
<div class="modal fade" id="addMoney" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">How much do you want to add?</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form method="POST" action="/wallet">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="100,000">
                    </div>
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

            <form method="POST" action="/wallet">
                @csrf
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="1,000">
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