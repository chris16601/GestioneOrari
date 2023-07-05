@include('layouts.navbar')
<head>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/contactUs.css') }}">
</head>
<div class="card">
    <div class="m-3" style="text-align: center; color:white">
        <h2>Contact Us</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="" class="form-horizontal">
            <div class="row m-2" id="anagrafica">
                <div class="col-md-4">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-control" placeholder="">
                </div>
                <div class="col-md-4">
                    <label for="cognome">Cognome</label>
                    <input type="text" id="cognome" name="cognome" class="form-control" placeholder="">
                </div>
                <div class="col-md-4">
                    <label class="label-form" for="email">Email</label>
                    <input type="email" size="30" id="email" name="email" class="form-control" placeholder="mariorossi23@example.com">
                </div>
            </div>
            <div class="row m-3" id="note">
                <label class="label-form" for="note_note">Note</label>
                <textarea id="note_note" rows="12">
                </textarea>
            </div>
            <div id="buttons">
                <button class="btn btn-primary m-3" type="submit" id="submit" style="float: inline-end;">Invio</button>
            </div>
        </form>
    </div>
</div>

@include('layouts.footer')
