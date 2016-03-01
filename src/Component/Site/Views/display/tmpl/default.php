<?php // no direct access

namespace Joosco\Views\joosco\tmpl;

defined('_JEXEC') or die('Restricted access');

class JooscoTmpl
{
    public static $path;
    public static $session;
    public static $store;
    public static $currentNode;

    public static $option;
    public static $view;
    public static $itemid;

    // Generate URLs for the links to spaces or content
    public function getURL($node)
    {
        $path = $this->path;

        $option = $this->option;
        $view = $this->view;
        $itemid = $this->itemid;

        $result = null;

        if ($node->type == '{http://www.alfresco.org/model/content/1.0}content') {
            $contentData = $node->cm_content;
            if ($contentData != null) {
                $result = $contentData->getUrl();
            }
        } else {
            $result = 'index.php'.
                        '?option='.$option.
                        '&view='.$view.
                        '&Itemid='.$itemid.
                        '&uuid='.$node->id.
                        '&name='.$node->cm_name.
                        '&path='.$path;
        }

        return $result;
    }

    public function getImageURL($current_type = '{http://www.alfresco.org/model/content/1.0}folder')
    {
        $result = null;
        if ($current_type == '{http://www.alfresco.org/model/content/1.0}content') {
            $result = 'post.gif';
        } else {
            $result = 'space_small.gif';
        }

        return $result;
    }

    public function outputRow($node)
    {
        print("<tr><td><img src='components/com_joosco/Site/assets/Images/".self::getImageURL($node->type)."'>&nbsp;&nbsp;<a href='");
        print(self::getURL($node));
        print("'>");
        print($node->cm_name);
        print('</a></td></tr>');
    }

    public function outputTable($title, $node, $type_filter, $empty_message)
    {
        print(
        '<table cellspacing=0 cellpadding=0 border=0 width=95% align=center>'.
        '   <tr>'.
        '       <td width="7"><img src="components/com_joosco/Site/assets/Images/blue_01.gif" width="7" height="7" alt=""></td><td background="components/com_joosco/Site/assets/Images/blue_02.gif"><img src="components/com_joosco/Site/assets/Images/blue_02.gif" width="7" height="7" alt=""></td>'.
        '       <td width="7"><img src="components/com_joosco/Site/assets/Images/blue_03.gif" width="7" height="7" alt=""></td></tr><tr><td background="components/com_joosco/Site/assets/Images/blue_04.gif"><img src="components/com_joosco/Site/assets/Images/blue_04.gif" width="7" height="7" alt=""></td>'.
        '       <td bgcolor="#D3E6FE">'.
        '           <table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><td><span class="mainSubTitle">'.$title.'</span></td></tr></table>'.
        '       </td>'.
        '       <td background="components/com_joosco/Site/assets/Images/blue_06.gif"><img src="components/com_joosco/Site/assets/Images/blue_06.gif" width="7" height="7" alt=""></td>'.
        '   </tr>'.
        '   <tr>'.
        '       <td width="7"><img src="components/com_joosco/Site/assets/Images/blue_white_07.gif" width="7" height="7" alt=""></td>'.
        '       <td background="components/com_joosco/Site/assets/Images/blue_08.gif"><img src="components/com_joosco/Site/assets/Images/blue_08.gif" width="7" height="7" alt=""></td>'.
        '       <td width="7"><img src="components/com_joosco/Site/assets/Images/blue_white_09.gif" width="7" height="7" alt=""></td>'.
        '   </tr>'.
        '   <tr>'.
        '       <td background="components/com_joosco/Site/assets/Images/white_04.gif"><img src="components/com_joosco/Site/assets/Images/white_04.gif" width="7" height="7" alt=""></td>'.
        '       <td bgcolor="white" style="padding-top:6px;">'.
        '           <table border="0" width="100%">');

        foreach ($node->children as $child) {
            if ($child->child->type == $type_filter) {
                self::outputRow($child->child);
            }
        }

        print(
        '         </table>'.
        '      </td>'.
        '      <td background="components/com_joosco/Site/assets/Images/white_06.gif"><img src="components/com_joosco/Site/assets/Images/white_06.gif" width="7" height="7" alt=""></td>'.
        '   </tr>'.
        '   <tr>'.
        '      <td width=7><img src="components/com_joosco/Site/assets/Images/white_07.gif" width="7" height="7" alt=""></td>'.
        '      <td background="components/com_joosco/Site/assets/Images/white_08.gif"><img src="components/com_joosco/Site/assets/Images/white_08.gif" width="7" height="7" alt=""></td>'.
        '      <td width=7><img src="components/com_joosco/Site/assets/Images/white_09.gif" width="7" height="7" alt=""></td>'.
        '   </tr>'.
        '</table>');
    }

    public function outputBreadcrumb($path)
    {
        $session = $this->session;
        $store = $this->store;

        $option = $this->option;
        $view = $this->view;
        $itemid = $this->itemid;

        print(
            '<table border="0" width="95%" align="center">'.
            '   <tr>'.
            '      <td>');

        $values = split("\|", $path);
        $home = $values[0];
        $path = $home;
        $id_map = array();
        for ($counter = 1; $counter < count($values); $counter += 2) {
            $id_map[$values[$counter]] = $values[$counter + 1];
        }

        print("<a href='index.php?option=".$option.'&view='.$view.'&Itemid='.$itemid."'><strong>".$home.'</strong></a>');
        foreach ($id_map as $id => $name) {
            $path .= '|'.$id.'|'.$name;
            print(" &gt; <a href='".self::getURL($session->getNode($store, $id))."'><strong>".$name.'</strong></a>');
        }

        print(
        '      </td>'.
        '   </tr>'.
        '</table>');
    }
}
?>

<!-- Header -->
<table cellspacing="0" cellpadding="2" width="95%" align="center">
    <tr>
        <td width=100%>
        <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td style="padding-right:4px; vertical-align: middle;"><img src="components/com_joosco/Site/assets/Images/AlfrescoLogo32.png" alt="Alfresco" title="Alfresco" style="border: 0;"></td>
            <td><img src="components/com_joosco/Site/assets/Images/titlebar_begin.gif" width="10" height="30"></td>
            <td width="100%" style="background-image: url(components/com_joosco/Site/assets/Images/titlebar_bg.gif)">
                <strong style="color: white;">Joosco Extension</strong>
            </td>
            <td><img src="components/com_joosco/Site/assets/Images/titlebar_end.gif" width="8" height="30"></td>
        </tr>
        </table>
        </td>
    </tr>
</table>
<br />

<?php
// Get the values and set the class variables
// JooscoTmpl::$path = $this->path;
// JooscoTmpl::$session = $this->session;
// JooscoTmpl::$store = $this->store;
// JooscoTmpl::$currentNode = $this->currentNode;

// JooscoTmpl::$option = $this->option;
// JooscoTmpl::$view = $this->view;
// JooscoTmpl::$itemid = $this->itemid;
?>

<?php
       // Output the breadcrumb
       // This is a path breadcrumb (http://en.wikipedia.org/wiki/Breadcrumb_%28navigation%29#Path)
       // JooscoTmpl::outputBreadcrumb(JooscoTmpl::$path);
?>
<br />
<?php
       // Output the spaces (folders)
       // JooscoTmpl::outputTable('Browse Spaces', JooscoTmpl::$currentNode, '{http://www.alfresco.org/model/content/1.0}folder', 'There are no spaces');
?>
<br />
<?php
       // Output the content (files)
       // JooscoTmpl::outputTable('Content Items', JooscoTmpl::$currentNode, '{http://www.alfresco.org/model/content/1.0}content', 'There is no content');
?>