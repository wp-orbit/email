<?php
namespace WPOrbit\Email;

class Emailer
{
    protected $from;
    protected $to;
    protected $subject = 'Set Email Subject';
    protected $message = '';
    protected $attachments = [];
    protected $headers = [];
    protected $html = true;

    public function __construct( $args = [] )
    {
        // Get admin email.
        $this->to = get_option( 'admin_email' );

        if ( isset( $args['from'] ) ) {
            $this->from = $args[ 'from' ];
        }
        if ( isset( $args['to'] ) ) {
            $this->to = $args['to'];
        }
        if ( isset( $args['subject'] ) ) {
            $this->subject = $args['subject'];
        }
    }

    public function dispatch()
    {
        // Apply HTML filter?
        if ( $this->html )
        {
            add_filter( 'wp_mail_content_type', function()
            {
                return 'text/html';
            } );
        }

        // Dispatch email.
        $result = wp_mail( $this->to, $this->subject, $this->message, $this->headers, $this->attachments );

        // Remove HTML filter.
        if ( $this->html )
        {
            remove_filter( 'wp_mail_content_type', function()
            {
                return 'text/html';
            } );
        }

        return $result;
    }
}