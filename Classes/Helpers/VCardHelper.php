<?php
/**
 * VCard helper class
 *
 * @author S'nce Group
 */

namespace Snce\VCardBundle\Classes\Helpers;

class VCardHelper {

    private $vCardFields;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->vCardFields = array();
    }

    /**
     * Shortcut to set contact name and it's formatted version
     *
     * @param string $firstName     Contact first name
     * @param string $lastName      Contact last name
     * @param string $additional    Contact additional info
     * @param string $prefix        Contact prefix name
     * @param string $suffix        Contact suffix name
     *
     */
    public function setName($firstName = '' ,$lastName = '' ,$additional = '' ,$prefix = '' ,$suffix = '' )
    {
        $nameComposed = utf8_encode( $firstName ) . ';' . utf8_encode( $lastName ) . ';' . utf8_encode( $additional ) . ';' . utf8_encode( $prefix ) . ';' . utf8_encode( $suffix );
        $nameFormatted = utf8_encode( $additional ) . ' ' . utf8_encode( $firstName ) .' '. utf8_encode( $lastName );

        $field="N";
        $this->addField($field ,$nameComposed ,':' );

        $field="FN";
        $this->addField($field ,$nameFormatted ,':' );
    }

    /**
     * Shortcut to set contact address
     *
     * @param string $name          Contact address name
     * @param string $extended      Contact extended address
     * @param string $street        Contact street
     * @param string $city          Contact city
     * @param string $region        Contact region
     * @param string $zip           Contact suffix name
     * @param string $country       Contact country
     * @param string $type          Address type (i.e. WORK, HOME)
     *
     */
    public function setAddress($name = '' ,$extended = '' ,$street = '' ,$city = '' ,$region = '' ,$zip = '' ,$country = '' ,$type = 'WORK')
    {
        $value = $name . ';' . $extended . ';' . $street . ';' . $city . ';' . $region . ';' . $zip . ';' . $country;

        $field = $field = $this->setFieldType('ADR', $type );
        $this->addField($field ,$value );
    }

    /**
     * Shortcut to set contact email
     *
     * @param string $email     Contact email
     * @param string $type      Email account type
     *
     */
    public function setEmail($email = '' ,$type = 'WORK' )
    {
        $field = $this->setFieldType('EMAIL', $type );
        $this->addField($field ,utf8_encode($email ) );
    }

    /**
     * Shortcut to set contact email
     *
     * @param string $organization     Contact organization
     *
     */
    public function setOrganization($organization = '' )
    {
        $field="ORG";
        $this->addField($field ,utf8_encode($organization ) );
    }

    /**
     * Shortcut to set contact phone
     *
     * @param string $phone     Contact phone number
     * @param string $type      Phone number account type
     *
     */
    public function setPhone($phone = '' ,$type = 'WORK' )
    {
        $field = $this->setFieldType('TEL', $type );
        $this->addField($field ,utf8_encode($phone ) );
    }

    /**
     * Shortcut to set contact URL
     *
     * @param string $url       Contact email
     * @param string $type      Email account type
     *
     */
    public function setUrl($url = '' ,$type = 'WORK' )
    {
        $field = $this->setFieldType('URL', $type );
        $this->addField($field ,utf8_encode($url ) );
    }

    /**
     * Shortcut to set social profile account
     *
     * @param string $url       Contact email
     * @param string $type      Email account type
     * @param string $username  Social username (not used in every social network)
     *
     */
    public function setSocialAccount($url = '' ,$type = '' ,$username = '')
    {
        $otherAttributes = array();
        if ($username != '')
        {
            $otherAttributes = array('X-USER' => $username);
        }

        $field = $this->setFieldType('X-SOCIALPROFILE', $type ,$otherAttributes  );
        $this->addField($field ,utf8_encode($url ) );
    }

    /**
     * Shortcut to set contact image
     *
     * @param string $imageUrl       Contact image path
     * @param string $type           Image type (JPEG)
     *
     */
    public function setImage($imageUrl = '' ,$type = 'JPEG' )
    {
        $otherAttributes = array('ENCODING' => 'b');
        $field = $this->setFieldType('PHOTO' ,$type ,$otherAttributes );

        $imageContent = file_get_contents($imageUrl );
        $imageContentBase64 = base64_encode($imageContent );

        $this->addField($field ,$imageContentBase64 );
    }

    /**
     * Field type string creation
     *
     * @param string    $field              VCard field
     * @param string    $type               VCard field type (i.e. WORK, HOME, ecc)
     * @param array     $otherAttributes    Other field attributes
     *
     * @return string
     *
     */
    private function setFieldType($field = '' ,$type = '' ,$otherAttributes = array() )
    {
        if($type != '' )
        {
            $fieldComposed = $field . ";TYPE=" . $type;
        }
        else
        {
            $fieldComposed= $field;
        }

        foreach( $otherAttributes as $key => $value ){
            $fieldComposed .= ";$key=" . $value;
        }

        return $fieldComposed;
    }

    /**
     * Add a new field to the VCard content
     *
     * @param string $field             VCard field
     * @param string $value             VCard field value
     * @param string $mainSeparator     VCard field separator
     *
     */
    public function addField( $field , $value, $mainSeparator = ':' )
    {
        $this->vCardFields[] = $field . $mainSeparator . $value;
    }

    /**
     * Return VCard
     *
     * @return string
     */
    public function generate()
    {
        $vCard = "BEGIN:VCARD\n";
        $vCard .= "VERSION:3.0\n";
        $vCard .= "PRODID:-//S'nce Group//SnceVCardBundle//EN\n";

        foreach($this->vCardFields as $field)
        {
            $vCard .= $field."\n";
        }

        $vCard .= "END:VCARD\n";

        return $vCard;
    }

} 