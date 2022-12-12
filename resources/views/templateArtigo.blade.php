<style type="text/css">
    @font-face {
        font-family: Arial;
        src: url('{{ public_path('css/arial.ttf') }}');
    }
</style>
<div style="font-family: Arial;">
    {{-- RESUMO --}}

    <div>
        <div style="text-align: {{$alinhamentoTitle}}; justify-content:center; text-transform: uppercase; font-weight: bold;">
            <span >
                {{$title}}
            </span>
        </div>
        <div style="display:block; text-align: end;">
            <p>{{$user}}</p>
            <p>{{$orientador}}</p>
        </div>

        <div style="margin:30px 0 30px 0; text-align: {{$alinhamentoTitle}}; font-size: {{$fontSizeTitle}}; font-weight: {{$pesoTitle}}; text-transform:{{$formatoTitle}};">
            <p>Resumo</p>
        </div>

        <div style="text-align:{{$alinhamentoText}}; justify-content: center; margin-bottom: 5px; font-size: {{$fontSizeText}}; line-height:{{$espacamentoTexto}};">
            @foreach ($resumo as $value)
                {{ $value }}
            @endforeach
        </div>
    </div>

    {{-- INTRODUÇÃO --}}

    <div>
        <p style="text-align: {{$alinhamentoTitle}}; font-size: {{$fontSizeTitle}}; font-weight: {{$pesoTitle}}; text-transform:{{$formatoTitle}}; margin:30px 0 30px 0;">Introdução</p>
        <div style="font-size:{{$fontSizeText}}; text-align:{{$alinhamentoText}}; line-height:{{$espacamentoTexto}};">
            @foreach ($introducao as $intro)
                {{$intro}}
            @endforeach
        </div>

    </div>

    {{-- DESENVOLVIMENTO --}}
    <div>
        @foreach ($desenvolvimento as $index => $content)
            <div>
                <div style="text-align: {{$alinhamentoTitle}}; font-size: {{$fontSizeTitle}}; font-weight: {{$pesoTitle}}; text-transform:{{$formatoTitle}}; margin:30px 0 30px 0;">
                    <p>{{ $index }}</p>
                </div>
                <div style="text-align:{{$alinhamentoText}}; line-height:{{$espacamentoTexto}}; font-size:{{$fontSizeText}};">
                    @foreach ($content as  $value)
                        {{$value}}
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- REFERENCIAS --}}
    <div>
        <div style="text-align: {{$alinhamentoTitle}}; font-size: {{$fontSizeTitle}}; font-weight: {{$pesoTitle}}; text-transform:{{$formatoTitle}}; margin:30px 0 30px 0;">
            <p>REFERÊNCIAS</p>
        </div>
        <div style="text-align:{{$alinhamentoText}}; line-height:{{$espacamentoTexto}}; font-size:{{$fontSizeText}};">
            @foreach ($referencias as $autor)
                {{$autor}}
            @endforeach
        </div>
    </div>
</div>
