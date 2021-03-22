# track-package
API para rastreio de encomendas no correio

### Como usar:

#### Rota:

##### /track-package (POST)
    > Parametros:
    > "_": "track_package",
    > "track_package": "YOUR TRACKING CODE HERE"

##### Exemplo de requisição usando o postman:

![RequisicaoRota](https://i.imgur.com/DcaBPCT.png)

##### Retorno:

![RetornoRota](https://i.imgur.com/U1uKgMH.png)

#### Link da API: https://rastreio-correios-api.herokuapp.com/

<hr>

#### Classe Individual

Para implementação dessa funcionalidade em seu proprio codigo, basta acessar a pasta "Classe Individual"
e acessar a classe <strong>TrackPackage.php</strong> e acessar o metodo <strong>trackPackage(trackingCode)</strong>
passando o codigo de rastreio como parametro.