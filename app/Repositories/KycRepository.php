<?php
namespace App\Repositories;

use App\Http\Resources\ProfileResource;
use App\Jobs\SasJob;
use App\Kyc;
use App\Http\Resources\KycResource;
use App\EmploymentData;
use App\SocialLink;
use App\Traits\UploadAble;
use App\UserKyc;
use App\AgentApproval;

class KycRepository
{
    protected $kyc;
    protected $employment;
    protected $sociallink;
    protected $userkyc;
    protected $agentApproval;

    use UploadAble;

    public function __construct(AgentApproval $agentApproval, Kyc $kyc, EmploymentData $employment, SocialLink $sociallink, UserKyc $userkyc)
    {
        $this->kyc = $kyc;
        $this->employment = $employment;
        $this->sociallink = $sociallink;
        $this->userkyc = $userkyc;
        $this->agentApproval = $agentApproval;
    }

    public function store($request)
    {
        $kyc = $this->kyc->create([
          "user_profile_id" => auth()->guard("profile")->user()->id,
          "dob" => $request->dob,
          "means_of_identity" => $request->means_of_identity,
          "status" => 0,
        ]);

        if ($kyc) {
            return new KycResource($kyc);
        }

        return response()->json(["status" => false, "message" => "Could not submit kyc request"]);
    }

    public function show($id)
    {
        $kyc = $this->kyc->findOrFail($id);
        if ($kyc) {
            return new KycResource($kyc);
        }

        return response()->json(["status" => false, "message" => "No matching record"]);
    }

    public function update($request) {
        \Log::info($request->all());
        $user = auth()->guard('profile')->user();

        /*** upload proof of employment***/
        if($request->has("proof_of_employment") ) {
            $data = $this->employment->where("user_profile_id", $user->id)->first();
            if(!empty($data)) {
                $photo = $this->uploadOne($request->proof_of_employment, 'employment');
                $data->update(["proof_of_employment" => $photo, 'last_location' => $request->location]);
                return response()->json(["status" => true, "message" => "File Uploaded successfully", "user" => new ProfileResource($user)]);
            }
            return response()->json(["status" => false, "message" => "File Upload failed"]);
        }

        /*** check and upload means of identification documents ***/
        if($request->has('means_of_id') ) {
            $means_of_id = $this->uploadOne($request->means_of_id, 'means_of_identity');
            $this->userkyc->set($user->id, 'means_of_identity', $means_of_id, $request->location);
            return response()->json(["status" => true, "message" => "Update successful", "user" => new ProfileResource($user)]);
        }
        elseif($request->has('residential_document') ) {
            $residential_doc = $this->uploadOne($request->residential_document, 'means_of_identity');
           $this->userkyc->set($user->id, 'residential_document', $residential_doc, $request->location);
           return response()->json(["status" => true, "message" => "Update successful", "user" => new ProfileResource($user)]);
        }
        elseif($request->has('photo') ) {
            $photo =  $this->uploadOne($request->photo, 'profile_photos');
            $this->userkyc->set($user->id, 'profile_photo', $photo, $request->location);
            return response()->json(["status" => true, "message" => "Update successful", "user" => new ProfileResource($user)]);
        }
        else {
            $keys = $request->except(['_token', 'userid', 'photo', 'residential_document', 'proof_of_employment', 'means_of_id', 'last_location']);
            $status = false;
            foreach ($keys as $key => $value)
            {
              $status =  $this->userkyc->set($user->id, $key, $value, $request->location);
            }

            if($status) {
                return response()->json(["status" => true, "message" => "Update successful", "user" => new ProfileResource($user)]);
            }
            return response()->json(["status" => false, "message" => "Could not save details"]);
        }



    }

    public function updateKyc($request)
    {

        $userId = auth()->guard('profile')->user()->id;
        $location = \geoip(request()->getClientIp())->getAttribute('city').', '.\geoip(request()->getClientIp())->getAttribute('country');

        if($request->has("proof_of_employment") && !empty($request->proof_of_employment)) {
            $data = $this->employment->where("user_profile_id", $userId)->first();
            if(!empty($data)) {
                $photo = $this->imageUpload($request->proof_of_employment);
                $data->update(["proof_of_employment", $photo, "last_location" => $location]);
                return response()->json(["status" => true, "message" => "File Uploaded successfully"]);
            }
            return response()->json(["status" => true, "message" => "File Upload failed"]);
        }

        $found = $this->kyc->where("user_profile_id", $userId)->first();
        $points = $found->points;

        if ($found) {
            if ($request->hasFile("photo")) {
                $photo = $request->has('photo') ? $this->imageUpload($request) : "no-image.jpg";
                $point = !empty($found->profile_photo) ? 0 : 10;
                $data = array("profile_photo" => $photo, "last_location" => $location, "points" => ($point + $points));
            }

            if ($request->has("means_of_id") && $request->means_of_id !== "") {
                $photo = $request->has('means_of_id') ? $this->imageUpload($request) : "no-image.jpg";
                $point = !empty($found->means_of_id) ? 0 : 10;
                $data = array("means_of_identity" => $photo, "last_location" => $location, "points" => ($points + $point));
            }

            if ($request->has("residential_document") && !empty($request->residential_document) ) {
                $photo = $request->has('residential_document') ? $this->imageUpload($request) : "no-image.jpg";
                $point = !empty($found->residential_document) ? 0 : 10;
                $data = array("residential_document" => $photo, "last_location" => $location, "points" => ($points + $point));
            }

            if ($request->has("national_id") && !empty( $request->national_id)) {
                $point = !empty($found->national_id) ? 0 : 10;
                $data = array("national_id" =>  $request->national_id, "last_location" => $location, "points" => ($point + $points));
            }

            if ($request->has("residential_address") && !empty( $request->residential_address)) {
                $point = !empty($found->residential_address) ? 0 : 10;
                $data = array("residential_address" =>  $request->residential_address, "last_location" => $location, "points" => ($point + $points));
            }

            if ($request->has("dob") && $request->dob !== "") {
                $data = array("dob" => $request->dob, "last_location" => $location);
            }

            if ($request->has("bvn") && $request->bvn !== "") {
                $respone = \Payment::verifyBVN($request->bvn);


                if ($respone === 'Unauthorized') {
                    return response()->json(["message" => "A network error occured", "status" => false]);
                }

                if (!$respone["status"]) {
                    return response()->json(["message" => "Valid BVN", "status" => false, "data" => $respone]);
                }

                if ($respone["data"]["mobile"] !== auth()->guard("profile")->user()->phone_number) {
                    return response()->json([
            "status" => false,
            "message" => "BVN phone number didn't match profile phone number",
          ]);
                }
                $data = array("bvn" => $respone["data"]["bvn"], "status" => 1);
            }

            $save = $found->update($data);

            if ($save) {
                return response()->json(["status" => true, "message" => "Update successful"]);
            }
            return response()->json(["status" => false, "message" => "Could not save changes"]);
        }
        return response()->json(["status" => false, "message" => "No matching record found"]);
    }

    public function delete($id)
    {
        $kyc = $this->kyc->find($id);
        if ($kyc) {
            $delete = $kyc->delete($id);
            if ($delete) {
                return new KycResource($delete);
            }
            return response()->json(["status" => false, "message" => "Could not delete record"]);
        }
        return response()->json(["status" => false, "message" => "No matching record found"]);
    }

    public function imageUpload($request)
    {
        if ($file = $request->file('photo')) {
            $fileName = time().time().'.'.$request->photo->getClientOriginalExtension();
            $target_dir = storage_path("/app/public/profile_photos");

            if ($file->move($target_dir, $fileName)) {
                $fileNameToStore = $fileName;
            } else {
                $fileNameToStore = "no-image.jpg";
            }
            return $fileNameToStore;
        }

        if ($file = $request->file('means_of_id')) {
            $fileName = time().time().'.'.$request->means_of_id->getClientOriginalExtension();
            $target_dir = storage_path("/app/public/means_of_identity");

            if ($file->move($target_dir, $fileName)) {
                $fileNameToStore = $fileName;
            } else {
                $fileNameToStore = "no-image.jpg";
            }
            return $fileNameToStore;
        }

        if ($file = $request->file('residential_document')) {
            $fileName = time().time().'.'.$request->residential_document->getClientOriginalExtension();
            $target_dir = storage_path("/app/public/means_of_identity");

            if ($file->move($target_dir, $fileName)) {
                $fileNameToStore = $fileName;
            } else {
                $fileNameToStore = "no-image.jpg";
            }
            return $fileNameToStore;
        }

        if ($file = $request->file('proof_of_employment')) {
            $fileName = time().time().'.'.$request->proof_of_employment->getClientOriginalExtension();
            $target_dir = storage_path("/app/public/employment");

            if ($file->move($target_dir, $fileName)) {
                $fileNameToStore = $fileName;
            } else {
                $fileNameToStore = "no-image.jpg";
            }
            return $fileNameToStore;
        }
    }

    public function socialHandle($request) {

        $user = auth()->guard('profile')->user();
        $kyc = Kyc::where("user_profile_id", $user->id)->first();
        $social_handles = $this->sociallink->where("user_profile_id", $user->id)->get();

        $socials[] = $request->has('facebook') && !empty($request->facebook) ? ['name' => 'facebook', 'handle' => $request->facebook] : null;
        $socials[] = $request->has('twitter') && !empty($request->twitter) ? ['name' => 'twitter', 'handle' => $request->twitter] : null;
        $socials[] = $request->has('instagram') && !empty($request->instagram) ? ['name' => 'instagram', 'handle' => $request->instagram] : null;
        $location = $request->location;

        foreach($socials as $social) {
            if(!empty($social)) {
                if(!$social_handles || count($social_handles) > 0) {
                    if(!empty($social['name']) && !empty($social['handle'])) {
                        $this->sociallink->where('user_profile_id', $user->id)->where("name", $social["name"])->update([
                            "handle" => $social["handle"],
                            "last_location" => $location
                        ]);
                    }
                }
                else {
                    $this->sociallink->create([
                        "user_profile_id" => $user->id,
                        'name' => $social['name'],
                        'handle' => $social['handle'],
                        'last_location' => $location,
                        "points" => 10
                    ]);
                    if(!empty($kyc)) {
                        $kyc->update(["points" => 10]);
                    }
                    $details = [
                        'first_name' => $user->first_name,
                        'type' => 'submission',
                        'email' => $user->email
                    ];
                    \dispatch(new SasJob($details));
                }
            }
        }

        return response()->json(["status" => true, "message" => "Social details saved successfully"]);

    }

    public function employmentHistory($request) {
      $user = auth()->guard("profile")->user();
      $history = $this->employment->where("user_profile_id", $user->id)->first();
      //$location = $this->getLocation($request->longitude, $request->latitude)['formatted_address'];
      $location = $request->location;
      $kyc = Kyc::where("user_profile_id", $user->id)->first();

      if($history && !empty($history)) {
          $save =  $history->update([
              "employment_status" => $request->employment_status,
              "employer" => $request->employer,
              "employment_type" => $request->employment_type,
              "last_location" => $location,
              "salary" => $request->salary
          ]);
      }
      else {
          $save =  $this->employment->create([
              "user_profile_id" => $user->id,
              "employment_status" => $request->employment_status,
              "employer" => $request->employer,
              "employment_type" => $request->employment_type,
              "last_location" => $location,
              "salary" => $request->salary,
              "points" => 10
          ]);
      }

      if($save) {
          if(!empty($kyc)) {
              $kyc->update(["points" => 10]);
          }
          return response()->json(["status" => true, "message" => "Employment details saved"]);
      }

      return response()->json(["status" => false, "message" => "Could not save employment details"]);
    }

}
