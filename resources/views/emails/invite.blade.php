<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Convite para Recrutamento</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
  }

  .container {
    background-color: #ffffff;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
  }

  h1 {
    color: #2d89ef;
  }

  p {
    color: #555;
    font-size: 16px;
  }

  .button {
    display: inline-block;
    padding: 12px 24px;
    margin: 20px 0;
    background-color: #2d89ef;
    color: #ffffff;
    text-decoration: none;
    font-size: 18px;
    border-radius: 5px;
  }

  .footer {
    font-size: 12px;
    color: #999;
    margin-top: 20px;
  }
  </style>
</head>

<body>

  <div class="container">
    <h1>Convite para nossa equipe!</h1>
    <p>Olá, <strong>{{ $email }}</strong>!</p>
    <p>Estamos felizes em convidá-lo para se juntar à nossa equipe.</p>
    <p>Para concluir seu cadastro, clique no botão abaixo:</p>

    <a href="{{ $link }}" class="button">Aceitar Convite</a>

    <p>Este link é válido por <b>24 horas</b>.</p>

    <p>Se você não se candidatou, pode ignorar este e-mail.</p>

    <p class="footer">© {{ date('Y') }} TechnologySolutions. Todos os direitos reservados.</p>
  </div>

</body>

</html>