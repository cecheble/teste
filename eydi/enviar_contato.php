<?php 
 
//Pega os dados postados pelo formulário HTML e os coloca em variaveis
if (eregi('tempsite.ws$|locaweb.com.br$|hospedagemdesites.ws$|websiteseguro.com$', $_SERVER[HTTP_HOST])) {
//substitua na linha acima a aprte locaweb.com.br por seu domínio.
$email_from='mendonca@mendoncaconsultoria.com.br';	// Substitua essa linha pelo seu e-mail@seudominio
}else {
$email_from = "mendonca@" . $_SERVER[HTTP_HOST];         
//    Na linha acima estamos forçando que o remetente seja 'webmaster@',
// você pode alterar para que o remetente seja, por exemplo, 'contato@'.
}
 
 
if( PATH_SEPARATOR ==';'){ $quebra_linha="\r\n";
 
} elseif (PATH_SEPARATOR==':'){ $quebra_linha="\n";
 
} elseif ( PATH_SEPARATOR!=';' and PATH_SEPARATOR!=':' )  {echo ('Esse script não funcionará corretamente neste servidor, a função PATH_SEPARATOR não retornou o parâmetro esperado.');
 
}
 
//pego os dados enviados pelo formulário 
$nome_para = $_POST["name"]; 
$email = $_POST["email"]; 
$mensagem = $_POST["message"]; 
$assunto = $_POST['subject'];
//formato o campo da mensagem 
$mensagem = wordwrap( $mensagem, 50, "<br>", 1); 
 
//valido os emails 
if (!ereg("^([0-9,a-z,A-Z]+)([.,_]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$", $email)){ 
 
echo"<center>Digite um email valido</center>"; 
echo "<center><a href=\"javascript:history.go(-1)\">Voltar</center></a>"; 
exit; 
 
} 
 
$arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : FALSE; 
 
if(file_exists($arquivo["tmp_name"]) and !empty($arquivo)){ 
 
$fp = fopen($_FILES["arquivo"]["tmp_name"],"rb"); 
$anexo = fread($fp,filesize($_FILES["arquivo"]["tmp_name"])); 
$anexo = base64_encode($anexo); 
 
fclose($fp); 
 
$anexo = chunk_split($anexo); 
 
 
$boundary = "XYZ-" . date("dmYis") . "-ZYX"; 
 
$mens = "--$boundary" . $quebra_linha . ""; 
$mens .= "Content-Transfer-Encoding: 8bits" . $quebra_linha . ""; 
$mens .= "Content-Type: text/html; charset=\"utf-8\"" . $quebra_linha . "" . $quebra_linha . ""; //plain 
$mens .= "Através do Webmail com arquivo anexado <br>$nome_para <br>$email<br>$telefone<br><br> $mensagem" . $quebra_linha . ""; 
$mens .= "--$boundary" . $quebra_linha . ""; 
$mens .= "Content-Type: ".$arquivo["type"]."" . $quebra_linha . ""; 
$mens .= "Content-Disposition: attachment; filename=\"".$arquivo["name"]."\"" . $quebra_linha . ""; 
$mens .= "Content-Transfer-Encoding: base64" . $quebra_linha . "" . $quebra_linha . ""; 
$mens .= "$anexo" . $quebra_linha . ""; 
$mens .= "--$boundary--" . $quebra_linha . ""; 
 
$headers = "MIME-Version: 1.0" . $quebra_linha . ""; 
$headers .= "From: $email " . $quebra_linha . ""; 
$headers .= "Return-Path: $email" . $quebra_linha . ""; 
$headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"" . $quebra_linha . ""; 
$headers .= "$boundary" . $quebra_linha . ""; 
 
 
//envio o email com o anexo 
$status = mail($email_from,$assunto,$mens,$headers, "-r".$email_from); 
 
	if($status) {
		echo "<script> alert('enviado com sucesso!'); </script>"; //mensagem de form enviado com sucesso.
	}
	else {
		echo "<script alert('Falha ao enviar!'); </script>"; //mensagem de erro no envio.
	}
	echo "<script> window.location.href = '../../'; </script>"; //mudar o site para redirecionar após o envio do form.    

 
} 
 
//se nao tiver anexo 
else{ 
    $headers  = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

/*abaixo contém os dados que serão enviados para o email
cadastrado para receber o formulário*/
         
       $corpo = "Através do Webmail\n";
       $corpo .= "Nome: " . $nome_para . "\n";
       $corpo .= "Email: " . $email . "\n";
	   $corpo .= "" . $telefone . "\n";
       $corpo .= "Comentários: " . $mensagem . "\n";
     
       $email_to = 'mendonca@mendoncaconsultoria.com.br'; //não esqueça de substituir este email pelo seu.
	   
      
    $status = mail($email_from, $assunto, $corpo, $headers); //enviando o email.

	if($status) {
		echo "<script> alert('enviado com sucesso!'); </script>"; //mensagem de form enviado com sucesso.
	}
	else {
		echo "<script alert('Falha ao enviar!'); </script>"; //mensagem de erro no envio.
	}
	echo "<script> window.location.href = '../../'; </script>"; //mudar o site para redirecionar após o envio do form.    

	
} 
 
?>