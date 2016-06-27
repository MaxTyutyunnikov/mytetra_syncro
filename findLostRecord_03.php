<?php
 
  $dataBaseDir="./base";
  $mytetraXml="./mytetra.xml";

  // ��������� DOM-������ ��������� �����
  $dom = new DomDocument;
  if( !$dom->load($mytetraXml) )
  {
    echo "Bad XML file ".$mytetraXml."\n";
    exit(1);
  }   

  // �������� ������� XML-���������
  $root=$dom->documentElement->getElementsByTagName("content")->item(0);

  $noCryptRecord=array();
  $cryptRecord=array();

  // ������� ������ ��������� � ��������
  $dirList = array();
  if($handle = opendir($dataBaseDir)) 
  {
    while($entry = readdir($handle)) 
    {
      if(is_dir($dataBaseDir."/".$entry)) 
      {
        echo "Check dir: ".$entry."\n";

        $result=findRecordByDirName($root, $entry);

        // ���� ���������� ������ ������������ � XML-�����
        if( $result['isExists']==true )
          echo "Ok"."\n";
        else
        {
          // ����� ���������� �� �������� � XML-����� � ��������� ���������� ��� �������������

          // ����� ���������� �� ��������� HTML-�����, ������������� ��� ���� ��� ���

        }
          
      
      }
    }

    closedir($handle);
  }







  function findRecordByDirName($element, $dirName)
  {
    $nodeId=$element->getAttribute("id");
    echo "Node ID:".$nodeId."\n";

    $recordtableElement=getRecordTable($element);

    // ���� � ���� ���� ������� �������� �������, ����� ���������� �������� � ��������
    if($recordtableElement!==false)
      foreach($recordtableElement->getElementsByTagName("record") as $record)
        downloadRecord($record);

    // ����������� �����
    foreach($element->childNodes as $currentElement)
      if($currentElement->nodeName==="node")
        if($currentElement->getAttribute("crypt")==="1")
          continue;
        else
          downloadRecurse( $currentElement );
  }


?>