<?php
namespace WPOrbit\Email;

class Emailer
{

    /**
     * @var string From name
     */
    protected $fromName = '';

    /**
     * @var string From email
     */
    protected $fromEmail = '';

    /**
     * @var mixed Email target address.
     */
    protected $to;

    /**
     * @var mixed|string The email subject.
     */
    protected $subject = '';

    /**
     * @var string The email body.
     */
    protected $message = '';

    /**
     * @var array Absolute paths to files.
     */
    protected $attachments = [];

    /**
     * @var array Email headers.
     */
    protected $headers = [];

    /**
     * @var bool Use HTML if true.
     */
    protected $html = true;

    public function __construct( $args = [] )
    {
        // Get admin email.
        $this->to = get_option( 'admin_email' );

        // Set arguments.
        if ( isset( $args['to'] ) ) {
            $this->to = $args['to'];
        }
        if ( isset( $args['subject'] ) ) {
            $this->subject = $args['subject'];
        }
    }

    /**
     * @param $email
     * @param string $name
     * @return $this
     */
    public function setFrom( $email = '', $name = '' )
    {
        $this->fromName = $name;
        $this->fromEmail = $email;
        return $this;
    }

    /**
     * @param $string
     * @return $this
     */
    public function setMessage( $string )
    {
        $this->message = $string;
        return $this;
    }

    /**
     * @param $string
     * @return $this
     */
    public function setSubject( $string )
    {
        $this->subject = $string;
        return $this;
    }

    /**
     * @param $string
     * @return $this
     */
    public function addHeader( $string )
    {
        $this->headers[] = $string;
        return $this;
    }

    /**
     * @param $path
     * @return $this
     */
    public function addAttachment( $path )
    {
        $this->attachments[] = $path;
        return $this;
    }

    protected function prepareHeaders()
    {
        // Set from header.
        $fromHeader = '' == $this->fromName ? $this->fromEmail : "{$this->fromName} <{$this->fromEmail}>";
        $this->addHeader( 'From: ' . $fromHeader );

        // Apply HTML filter?
        if ( $this->html )
        {
            // Append the text/html headers.
            $this->addHeader( 'Content-Type: text/html; charset=UTF-8' );
        }
    }

    /**
     * Send the email through wp_mail().
     * @return bool
     */
    public function dispatch()
    {
        $this->prepareHeaders();

        // Dispatch email.
        $result = wp_mail( $this->to, $this->subject, $this->message, $this->headers, $this->attachments );

        return $result;
    }
}