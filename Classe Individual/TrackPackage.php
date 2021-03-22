<?php

namespace Igor\Api;

class TrackPackage
{
    public function trackPackage($trackingCode)
    {
        $html = $this->connectToWs("https://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm", $trackingCode);
        if($this->verifyError($html)){
            return false;
        }
        $elements = $this->getElementsFromDOM($html);
        $response = $this->removeEmptySpaces($elements);
        $tracked = $this->getResponse($response);
        return $tracked;
    }

    private function getResponse($response)
    {
        foreach ($response as $key => $resp){
            $replaced = str_replace(',', ' ', $resp);
            $response[$key] = $replaced;
        }

        $resp = [];

        for ($i = 0; $i < count($response); $i+=2){
            array_push($resp, [
                'location' => $response[$i],
                'msg' => $response[$i+1]
            ]) ;
        }
        return $resp;
    }

    private function removeEmptySpaces($elements)
    {
        $valid = [
            'á', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú',
            'A', 'B', 'C', 'D' , 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
            'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g',
            'h' , 'i' , 'j' ,'k' , 'l' ,'m' , 'n', 'o', 'p', 'q',
            'r', 's', 't', 'u', 'v', 'w', 'x' , 'y', 'z', '1', '2',
            '3', '4', '5', '6', '7', '8', '9', '0', ':', '/'
        ];

        foreach ($elements as $key => $resp){
            $string = '';
            $resp = $this->removeAccents($resp);
            for ($i = 0; $i < strlen($resp); $i++){
                if(in_array($resp[$i], $valid)){
                    if(!in_array($resp[$i+1], $valid)){
                        $string .= $resp[$i].',';
                    }else{
                        $string .= $resp[$i];
                    }
                }
            }
            $response[$key] = $string;
        }
        return $response;
    }

    private function connectToWs($url, $trackingCode)
    {
        $post = array('Objetos' => $trackingCode);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($post));
        $output = curl_exec($ch);
        curl_close($ch);
        $html = utf8_encode($output);

        return $html;
    }

    private function verifyError($html)
    {
        $links = $this->startDOM($html, 'h4');
        $response = [];
        foreach ($links as $key => $link)
        {
            $response[$key] = utf8_decode($link->textContent);
        }

        if($response){
            return true;
        }else{
            return false;
        }

    }

    private function startDOM($html, $element)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $links = $dom->getElementsByTagName($element);
        return $links;
    }

    private function getElementsFromDOM($html)
    {
        $links = $this->startDOM($html, 'td');
        $response = [];
        foreach ($links as $key => $link)
        {
            $response[$key] = utf8_decode($link->textContent);
        }
        return $response;
    }

    private function removeAccents($string){
        $string = str_replace('ã', 'a', $string);
        $string = str_replace('â', 'a', $string);
        $string = str_replace('á', 'a', $string);
        $string = str_replace('Ã', 'A', $string);
        $string = str_replace('Â', 'A', $string);
        $string = str_replace('Á', 'A', $string);
        $string = str_replace('ç', 'c', $string);
        $string = str_replace('Ç', 'C', $string);
        $string = str_replace('ẽ', 'e', $string);
        $string = str_replace('ê', 'e', $string);
        $string = str_replace('Ê', 'E', $string);
        $string = str_replace('é', 'e', $string);
        $string = str_replace('Ẽ', 'E', $string);
        $string = str_replace('É', 'E', $string);
        $string = str_replace('í', 'i', $string);
        $string = str_replace('Í', 'I', $string);
        $string = str_replace('ó', 'o', $string);
        $string = str_replace('Ó', 'O', $string);
        $string = str_replace('Ú', 'U', $string);
        $string = str_replace('ú', 'u', $string);
        return $string;
    }
}