<?php
/**
 * Response represents an HTTP response with VCard HTTP Content-Type.
 *
 * @author S'nce Group
 */

namespace Snce\VCardBundle\Classes;

use Symfony\Component\HttpFoundation\Response;

class VCardResponse extends Response
{
    protected $data;
    protected $filename = 'vcard';

    /**
     * Constructor.
     *
     * @param mixed   $data    The response data
     * @param integer $status  The response status code
     * @param array   $headers An array of response headers
     */
    public function __construct($data = null, $status = 200, $headers = array())
    {
        parent::__construct('', $status, $headers);

        $this->setData($data);
    }

    /**
     * {@inheritDoc}
     */
    public static function create($data = null, $status = 200, $headers = array())
    {
        return new static($data, $status, $headers);
    }



    /**
     * Sets the data to be sent as VCard.
     *
     * @param string $data The response data
     *
     * @return VCardResponse
     */
    public function setData($data = null)
    {
        $this->data = $data;

        return $this->update();
    }

    /**
     * Sets the VCard filename.
     *
     * @param string $filename The VCard filename
     *
     * @return VCardResponse
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this->update();
    }

    /**
     * Updates the content and headers according to the VCard data
     *
     * @return VCardResponse
     */
    protected function update()
    {
        $this->headers->set('Content-Type', 'text/x-vcard; charset=UTF-8');
        $this->headers->set('Content-Disposition', 'attachment; filename=' . $this->filename . '.vcf;');
        return $this->setContent($this->data);
    }
}