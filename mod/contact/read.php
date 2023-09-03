<!DOCTYPE html>
<html>

  <body>
<section class="w-100 p-4 d-flex justify-content-center pb-4">
    <form action="?m=contact&a=mail" method="post" id="contact-form" class="text-center" style="width: 100%; max-width: 300px">
      <h2>Contacte-nos</h2>
<br>
      <!-- Name input -->
      <div class="form-outline mb-4">
        <input name="name" id="name" value="<?= $_SESSION['username'] ?>" type="text" readonly class="form-control">
        <label class="form-label" for="name">Nome</label>
      </div>  

      <!-- Email input -->
      <div class="form-outline mb-4">
        <input name="email" id="email" value="<?= $_SESSION['email'] ?>" readonly class="form-control">
        <label class="form-label" for="email">Email</label>
      </div>

      <!-- Subject input -->
      <div class="form-outline mb-4">
        <input type="text" id="subject" name="subject" class="form-control" placeholder="Escreve algo.." />
        <label class="form-label" for="subject">Assunto</label>
      </div>

      <!-- Message input -->
      <div class="form-outline mb-4">
        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Escreve algo.."></textarea>
        <label class="form-label" for="message">Mensagem</label>
      </div>

      <!-- Submit button -->
      <button class="btn btn-primary btn-block mb-4">Send</button>
    </form>
  </section>

  </body>
</html>