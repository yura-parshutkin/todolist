<?php

namespace Frontend\Controller;

use App\Exception\ValidateException;
use App\Job\RegistrationJob;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController
{
    /**
     * @var \Twig_Environment
     */
    protected $template;

    /**
     * @var Callable
     */
    protected $lastError;

    /**
     * @var RegistrationJob
     */
    protected $registrationJob;

    /**
     * @param \Twig_Environment $template
     * @param Callable $lastError
     * @param RegistrationJob $registrationJob
     */
    public function __construct(\Twig_Environment $template, $lastError, RegistrationJob $registrationJob)
    {
        $this->template        = $template;
        $this->lastError       = $lastError;
        $this->registrationJob = $registrationJob;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $error  = call_user_func($this->lastError, $request);

        return new Response($this->template->render('security/login.twig', [
            'action'       => 'login',
            'errors'       => $error ? [ $error ] : [],
        ]));
    }

    /**
     * @return Response
     */
    public function registrationAction()
    {
        return new Response($this->template->render('security/registration.twig', [
            'action' => 'registration',
            'errors' => []
        ]));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function registrationProcessAction(Request $request)
    {
        $job = new \stdClass();
        $job->email    = $request->request->get('email');
        $job->password = $request->request->get('password');

        try {
            $this->registrationJob->handle($job);
            return new RedirectResponse('/login');

        } catch (ValidateException $e) {
            return new Response($this->template->render('security/registration.twig', [
                'action' => 'registration',
                'errors' => $e->getErrors()
            ]));
        }
    }
}