<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CanvasRegisterRequest;
use App\Student;
use Auth;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class CanvasRegistrationController extends Controller
{
    /**
     * @var Redirector
     */
    private $redirector;

    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
    }

    /**
     * @throws Exception
     */
    public function __invoke(CanvasRegisterRequest $request)
    {
        if (!session()->has('canvasRegistrationData')) {
            return $this->redirector->route('register');
        }

        if ($request->isMethod('get')) {
            return $this->showRegisterForm();
        }
        if ($request->isMethod('post')) {
            return $this->handleRegisterForm($request);
        }

        throw new Exception('This route only supports GET and POST requests');
    }

    private function showRegisterForm()
    {
        return view('auth.canvas.register');
    }

    private function handleRegisterForm(CanvasRegisterRequest $request): RedirectResponse
    {
        $canvasData = $request->session()->get('canvasRegistrationData');
        $student = Student::create([
            'studentnr'                    => $request->get('studentnr'),
            'firstname'                    => $canvasData['firstName'],
            'lastname'                     => $canvasData['lastName'],
            'ep_id'                        => $request->get('education'),
            'pw_hash'                      => bcrypt(random_bytes(128)),
            // As user logs in through Canvas no password is necessary, but DB does not allow NULL (User can still set password from profile)
            'gender'                       => '-',
            'email'                        => $canvasData['email'],
            'userlevel'                    => 0,
            'registrationdate'             => date('Y-m-d H:i:s'),
            'locale'                       => $request->session()->get('locale', 'nl'),
            'canvas_user_id'               => $canvasData['canvasUserId'],
            'is_registered_through_canvas' => true,
            'email_verified_at'            => date('Y-m-d H:i:s'),
        ]);

        Auth::login($student);

        if ($student->educationProgram->educationprogramType->isActing()) {
            $route = 'home-acting';
        } else {
            $route = 'home-producing';
        }

        return $this->redirector->route($route)
            ->with('success',
                __('Je Werkplekleren account is aangemaakt, je kunt via Canvas inloggen op dit account.'));
    }
}
