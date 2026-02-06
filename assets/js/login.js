document.getElementById('formLogin').addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const msg = document.getElementById('mensagem');

    msg.innerText = "";

    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, senha })
        });

        const textoRetorno = await response.text();
        console.log("Resposta do servidor:", textoRetorno);

        const result = JSON.parse(textoRetorno);

        if (result.success) {
            window.location.href = 'dashboard.php';
        } else {
            msg.innerText = result.message;
        }

    } catch (error) {
        console.error(error);
        msg.innerText = "Erro ao conectar com o servidor.";
    }
});
