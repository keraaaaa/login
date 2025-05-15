<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Rating</title>
    <style>

.loader {
  font-size: 2em;
  font-weight: 900;
}
.loader > * {
  color: black;
}
.loader span {
  display: inline-flex;
}
.loader span:nth-child(2) {
  letter-spacing: -1em;
  overflow: hidden;
  animation: reveal 3500ms cubic-bezier(0.645, 0.045, 0.355, 1) infinite
    alternate;
}
@keyframes reveal {
  0%,
  100% {
    opacity: 0.5;
    letter-spacing: -1em;
  }
  50% {
    opacity: 1;
    letter-spacing: 0em;
  }
}
</style>

</head>
<body>

<div class="loader">
  <span>&lt;</span>
  <span>THANK YOU</span>
  <span>/&gt;</span>
</div>


</body>
</html>