<?php

namespace Apphp\Flashes;

use Illuminate\Support\Traits\Macroable;

class FlashesNotifier
{
    use Macroable;

    /**
     * Store data in session
     * @var SessionStore
     */
    protected $session;

    /**
     * Collection of messages
     * @var \Illuminate\Support\Collection
     */
    public $messages;

    /**
     * Constructor - creates a new instance
     * @param SessionStore $session
     */
    function __construct(SessionStore $session)
    {
        $this->session = $session;
        $this->messages = collect();
    }

    /**
     * Return an information message
     * @param  string|null $message
     * @return $this
     */
    public function info($message = null)
    {
        return $this->message($message, 'info');
    }

    /**
     * Return a success message
     * @param  string|null $message
     * @return $this
     */
    public function success($message = null)
    {
        return $this->message($message, 'success');
    }

    /**
     * Return an error message
     * @param  string|null $message
     * @return $this
     */
    public function error($message = null)
    {
        return $this->message($message, 'error');
    }

    /**
     * Return a warning message
     * @param  string|null $message
     * @return $this
     */
    public function warning($message = null)
    {
        return $this->message($message, 'warning');
    }

    /**
     * Save a message
     * @param  string|null $message
     * @param  string|null $type
     * @return $this
     */
    public function message($message = null, $type = null)
    {
        if (! $message instanceof Message) {
            $message = new Message(compact('message', 'type'));
        }

        $this->messages->push($message);

        return $this->flash();
    }

    /**
     * Add an attribute "hide" to the session
     * @return $this
     */
    public function hide()
    {
        return $this->updateLastMessage(['hide' => true]);
    }

    /**
     * Clear all messages
     * @return $this
     */
    public function clear()
    {
        $this->messages = collect();

        return $this;
    }

    /**
     * Modify the last added message with attributes
     * @param  array $overrides
     * @return $this
     */
    protected function updateLastMessage($overrides = [])
    {
        $this->messages->last()->update($overrides);

        return $this;
    }

    /**
     * Flash all messages to the session
     */
    protected function flash()
    {
        $this->session->flash('flash_notification', $this->messages);

        return $this;
    }
}