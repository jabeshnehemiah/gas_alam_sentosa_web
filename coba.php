<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form action="" id="form" enctype="multipart/form-data">
    <input type="text" name="nama" id="nama">
    <button type="submit">submit</button>
  </form>
</body>
<script>
  const form = document.getElementById('form')
  form.addEventListener('submit',event=>{
    event.preventDefault()
    console.log(new FormData(document.getElementById('form')).get('nama'))
  })
</script>
</html>