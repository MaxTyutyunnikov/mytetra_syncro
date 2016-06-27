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

        findRecordByDirName(0, $root, $entry); // ���� ������
        $result=findRecordByDirName(1, $root, $entry); // �����

        // ���� ���������� ������ ������������ � XML-�����
        if( $result==true )
          echo "Ok"."\n";
        else // ����� ���������� �� �������� � XML-����� � ��������� ���������� ��� �������������
        {
          // ��� ����� � ���������� �������
          $recordFileName=$dataBaseDir."/".$entry."/text.html";

          // ����� ���������� �� ��������� HTML-�����, ������������� ��� ���� ��� ���
          $file=fopen($recordFileName,"rb");
          if(!file)
          {
            echo("������ �������� ����� ".$recordFileName."\n");
            exit(1);
          }

          $buff=fread ($file, 7);
          if($buff=='RC5SIMP')
          {
            echo "Find lost crypt record in dir ".$entry."\n";
            $cryptRecord[]=$entry;
          }
          else
          {
            echo "Find lost non-crypt record in dir ".$entry."\n";
            $noCryptRecord[]=$entry;
          }
        }
      }
    }

    closedir($handle);
  }


  function findRecordByDirName($mode, $element, $dirName)
  {
    static $findRecord=false;

    // ����
    if($mode==0)
    {
      $findRecord==false;
      return false;
    }

    if($findRecord==true)
      return $findRecord;

    $nodeId=$element->getAttribute("id"); // echo "Node ID:".$nodeId."\n";

    $recordtableElement=getRecordTable($element);

    // ���� � ���� ���� ������� �������� �������, ����� ��������� ������ � ����� ������ ����� ����������
    if($recordtableElement!==false)
      foreach($recordtableElement->getElementsByTagName("record") as $record)
      {
        if($record->getAttribute("dir")==$dirName)
        {
          $findRecord=true;
          return result;
        }
      }

    // ����������� �����
    foreach($element->childNodes as $currentElement)
      if($currentElement->nodeName==="node")
        findRecordByDirName( $mode, $currentElement, $dirName );

    return $findRecord;
  }


  // ������� ������� ��� ����������� ���� ������� �������� �������
  // ���������� ������� ���� <recordtable> ��� false
  function getRecordTable($element)
  {
    // ������������, ���� �� � ���� ������� �������
    foreach($element->childNodes as $childElement)
      if($childElement->nodeName==="recordtable")
        return $childElement;
    
    return false;
  }

?>