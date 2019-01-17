<?php


namespace App\Services\Canvas;


use App\Repository\Eloquent\StudentRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Nette\NotImplementedException;

class CanvasAuthenticator
{
    /**
     * @var StudentRepository
     */
    private $studentRepository;
    /**
     * @var Redirector
     */
    private $redirector;

    public function __construct(StudentRepository $studentRepository, Redirector $redirector)
    {
        $this->studentRepository = $studentRepository;
        $this->redirector = $redirector;
    }

    public function authenticate(string $email, string $canvasUserId): RedirectResponse
    {
        $student = $this->studentRepository->findByEmail($email);

        if ($student === null) {
            throw new NotImplementedException('non-linked canvas student');
        }

        // In case there is no coupling yet, couple and login as
        if (!$student->isCoupledToCanvasAccount()) {
            $student->canvas_user_id = $canvasUserId;
            $this->studentRepository->save($student);

            Auth::login($student);

            if ($student->educationProgram->educationprogramType->isActing()) {
                $route = 'home-acting';
            } else {
                $route = 'home-producing';
            }

            return $this->redirector->route($route)->with('success',
                __('Jouw Canvas account is nu gekoppeld aan je Werkplekleren account.'));
        }

        if ($student->canvas_user_id !== $canvasUserId) {

            Auth::logout();

            return $this->redirector->route('login')->with('error',
                __('Het Werkplekleren account met als email :email is al gekoppeld aan een ander Canvas account.',
                    ['email' => $email]));
        }

        if ($student->canvas_user_id === $canvasUserId) {
            Auth::login($student);

            if ($student->educationProgram->educationprogramType->isActing()) {
                $route = 'home-acting';
            } else {
                $route = 'home-producing';
            }

            return $this->redirector->route($route)->with('success',
                __('Je bent successvol via Canvas ingelogd.'));
        }


    }


}