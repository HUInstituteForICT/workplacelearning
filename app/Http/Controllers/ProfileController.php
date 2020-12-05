<?php

declare(strict_types=1);
/**
 * This file (ProfileController.php) was created on 06/19/2016 at 16:01.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)
use App\Interfaces\StudentSystemServiceInterface;
//use App\Repository\Eloquent\StudentRepository;
use App\Services\CurrentUserResolver;
use App\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;

class ProfileController extends Controller
{
    /**
     * @var Redirector
     */
    private $redirector;

    /**
     * @var StudentSystemServiceInterface
     */
    private $studentSystemService;

    public function __construct(Redirector $redirector, StudentSystemServiceInterface $studentSystemService)
    {
        $this->middleware('auth');
        $this->redirector = $redirector;
        $this->studentSystemService = $studentSystemService;
    }

    public function show(CurrentUserResolver $currentUserResolver)
    {
        return view('pages.profile')
            ->with('student', $currentUserResolver->getCurrentUser())
            ->with('locales', Student::$locales)
            ->with('digestPeriods',
                array_reduce(Student::DIGEST_PERIODS, static function (array $carry, string $period): array {
                    $carry[$period] = __('elements.profile.labels.'.$period);

                    return $carry;
                }, [])
            );
    }

    public function update(Request $request, CurrentUserResolver $currentUserResolver)
    {
        $user = $currentUserResolver->getCurrentUser();

        $rules = [
            'firstname'     => 'required|max:255|min:3',
            'lastname'      => 'required|max:255|min:3',
            'digest_period' => [Rule::in(Student::DIGEST_PERIODS)],
        ];

        if (!$user->isRegisteredThroughCanvas()) {
            $rules['email'] = 'email|max:255|unique:student,email,'.$request->student_id.',student_id';
        }

        // Validate the input
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->route('profile')
                ->withErrors($validator)
                ->withInput();
        }

        $user->firstname = $request->get('firstname');
        $user->lastname = $request->get('lastname');
        if (!$user->isRegisteredThroughCanvas()) {
            $user->email = $request->get('email');
        }
        $user->locale = $request->get('locale');
        $user->digest_period = $request->get('digest_period');
        $user->save();

        return redirect()->route('profile')->with('success', __('general.edit-saved'));
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        // Just extend here, we don't need it anywhere else
        Validator::extend('validPassword', function ($attribute, $value, $parameters, $validator) use ($user) {
            return Hash::check($value, $user->pw_hash);
        });

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|validPassword',
            'new_password'     => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile')
                ->withErrors($validator)
                ->withInput();
        }

        $user->pw_hash = Hash::make($request->get('new_password'));
        $user->save();

        return redirect()->route('profile')->with('success', __('general.edit-saved'));
    }

    public function removeCanvasCoupling(
//        StudentRepository $studentRepository,
        CurrentUserResolver $currentUserResolver
    ): RedirectResponse {
        $student = $currentUserResolver->getCurrentUser();

        $student->canvas_user_id = null;

//        $studentRepository->save($student);
        $this->studentSystemService->saveStudent($student);

        session()->flash('success', __('De koppeling tussen je Canvas en Werkplekleren accounts is verwijderd.'));

        return $this->redirector->route('profile');
    }
}
