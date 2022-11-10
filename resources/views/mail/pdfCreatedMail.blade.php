@component('vendor.mail.html.message')
    <div style="text-align: center; margin-bottom: 30%">
        <h1>PDF Gerado!</h1>
    </div>
    <div style="text-align: center; margin-bottom: 30%">
        Olá {{ $user->name }},
        seu pdf foi gerado com sucesso, <br>
        Use o link para acessa-lo. <br>
        <button style=" margin-top: 10%;background-color: rgb(218, 104, 28); width: 200px; height: 50px; cursor: pointer;">
            <a style="text-decoration: none; font-weight: bold; color: white;" href={{$link}}>Acessar PDF</a>
        </button>
    </div>
@endcomponent
