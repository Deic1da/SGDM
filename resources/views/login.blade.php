<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SGDM</title>
        <link href="{{ asset('css/Login.css') }}" rel="stylesheet">
</head>
<body>
    <section class="login" id="login">
        <h1>Login</h1>
        <form class="formLogin" action="" method="post">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="xxx@xxx.xxx">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="********">
            <div class="selectBoxs">
                <div class="selectBoxLine1">
                    <input type="checkbox" id="mostrarSenha" name="mostrarSenha">
                    <label for="mostrarSenha">Mostrar Senha</label>
                </div>
                <div class="selectBoxLine2">
                    <div class="selectBoxLine2Dois">
                        <input type="checkbox" id="manterLogin" name="manterLogin">
                        <label for="manterLogin">Manter Login</label>
                        <button type="submit">Entrar</button>
                    </div>
                </div>
            </div>
            </form>
    </section>
    <div class="divisorMeio"></div>
    <section class="cadastro" id="cadastro">
        <h1>Cadastro</h1>
        <form class="formCadastro" action="" method="post">
            <div class="dadosForm">

                <div class="esquerda">
                    <label for="cadastroEmail">E-mail</label>
                    <input type="email" id="cadastroEmail" name="email" placeholder="xxx@xxx.xxx">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome completo">
                    <label for ="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                    <label for="cadastroSenha">Senha</label>
                <input type="password" id="cadastroSenha" name="senha" placeholder="Mínimo 8 caracteres">
                <label for="confirmarSenha">Confirmar Senha</label>
                <input type="password" id="confirmarSenha" name="confirmarSenha" placeholder="********">
            </div>
            <div class="direita">
                
                <label for="cep">CEP</label>
                <input type="text" id="cep" name="cep" placeholder="00000-000">
                <label for="estado">Estado</label>
                <select name="estado" id="estado">
                    <option value="">Selecione o estado</option>
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>   
                </select>
                <label for="municipio">Município</label>
                <select name="municipio" id="municipio">
                    <option value="">Selecione o município</option>
                </select>
                <label for="bairro">Bairro</label>
                <input type="text" id="bairro" name="bairro" placeholder="Nome do bairro">
                <label for="logradouro">Logradouro</label>
                <input type="text" id="logradouro" name="logradouro" placeholder="Nome do logradouro">
                <label for="numero">Número</label>
                <input type="text" id="numero" name="numero" placeholder="Número da residência">
            </div>
        </div>
        <button class="btncadastro" type="submit">Finalizar Cadastro</button>
        </form>
        
    </section>
</body>
</html>