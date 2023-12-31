<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller as mCtl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Helper, DB, QrCode;

class ApiController extends mCtl
{
    protected $user;
    protected $qr_size;

    public function __construct(Request $request) {
        $this->qr_size = 500;

        $loginToken = $request->loginToken;
        if(!($loginToken)){
            $allowed_endpoint_without_token = ["login", "register"];
            if (in_array($request->path(), $allowed_endpoint_without_token)){
                
            }else{
                return Helper::composeReply2("ERROR", "login token required");
            }
        }
        $user = DB::select("
            SELECT * FROM user WHERE USER_TOKEN = ?
        ", [$loginToken]);
        if (len($user) == 0){
            return Helper::composeReply2("ERROR", "user not found");
        }
        $this->user = $user[0];
    }


    public function login(Request $request){
        $nip = $request->nip;
        $password = $request->password;

        $user = DB::select("
            SELECT A.*, B.R_VALUE as USER_TYPE_VALUE, C.R_VALUE as USER_JABATAN_VALUE, D.*
            FROM _user as A
            JOIN _reference as B ON A.USER_TYPE = B.R_ID
            JOIN _reference as C ON A.USER_JABATAN = C.R_ID
            JOIN dinas as D ON A.USER_OPD = D.DINAS_ID
            WHERE A.USER_NIP = ? AND A.USER_PASSWORD_HASH = ?
        ", [$nip, $password]);
        if(count($user) == 0){
            return Helper::composeReply2("ERROR", "User tidak ditemukan, harap periksa kembali NIP dan Password anda");
        }
        $user = $user[0];
        
        $new_token = hash('sha256', md5($user->{"USER_ID"} . time()));
        DB::table("_user")->where("USER_ID", $user->{"USER_ID"})->update([
            "USER_TOKEN" => $new_token
        ]);
        $user->{"USER_TOKEN"} = $new_token;
        
        return Helper::composeReply2("SUCCESS", "Data user", $user);
    }


    public function master(Request $request){
        $data["DINAS"] = DB::table("dinas")->get();
        $data["KONDISI"] = DB::table("_reference")->where("R_CATEGORY", "KONDISI")->get();
        $data["KEBERADAAN"] = DB::table("_reference")->where("R_CATEGORY", "KEBERADAAN")->get();
        return Helper::composeReply2("SUCCESS", "Data master", $data);
    }


    public function barangGet(Request $request){
        $by = $request->by;
        $barang = DB::select("
            SELECT A.*, B.*, C.R_VALUE as BARANG_KONDISI_VALUE, D.R_VALUE as BARANG_KEBERADAAN_VALUE
            FROM barang as A
            JOIN dinas as B ON A.BARANG_OPD = B.DINAS_ID
            JOIN _reference as C ON A.BARANG_KONDISI = C.R_ID
            JOIN _reference as D ON A.BARANG_KEBERADAAN = D.R_ID
            ".$by."
        ", []);
        return Helper::composeReply2("SUCCESS", "Data barang", $barang);
    }


    public function barangInsert(Request $request){
        $kode = $request->kode;
        $nama = $request->nama;
        $opd = $request->opd;
        $kondisi = $request->kondisi;
        $keberadaan = $request->keberadaan;
        $keterangan = $request->keterangan;
        $file = $request->foto;

        $base64String = $request->base64String;
        $fileBinaryData = base64_decode($base64String);
        $uploadFile = "image-" . substr(md5(date("YmdHis") . $base64String), 0, 10) . ".jpg";
        $storagePath = "storage/";
        file_put_contents($storagePath . $uploadFile, $fileBinaryData);
        $fileSize = strlen($fileBinaryData);
        if ($fileSize > 4096000) {
            return Helper::composeReply2("ERROR", "Batas ukuran file maksimal adalah 4MB");
        }
        $fileExt = "jpg";
        if (!in_array($fileExt, ["jpg", "jpeg", "png"])) {
            return Helper::composeReply2("ERROR", 'Format file tidak diizinkan');
        }

        $cek = DB::select("
            SELECT * FROM barang WHERE BARANG_KODE_SENSUS = ?
        ", [$kode]);
        if(count($cek) > 0){
            return Helper::composeReply2("ERROR", "Kode sensus " . $kode . " telah digunakan");
        }
        
        // $fileName = $file->getClientOriginalName();
        // $fileSize = $file->getClientSize();
        // if($fileSize > 4096000) return Helper::composeReply2("ERROR", "Batas ukuran file maksimal adalah 4MB");
        // $fileExt = strtolower($file->getClientOriginalExtension());
        // if(in_array($fileExt, ["jpg", "jpeg", "png"]) === false) return Helper::composeReply2("ERROR", 'Format file tidak diizinkan');
        // $uploadFile = "image-" . substr(md5(date("YmdHis"). $file),0,10) . "." . $fileExt;
        // $file->move("storage/", $uploadFile);

        $svg = QrCode::size($this->qr_size )->generate($kode);
        $save = DB::table("barang")->insertGetid([
            "BARANG_KODE_SENSUS" => $kode,
            "BARANG_QR_SVG" => str_replace("\n", " ", $svg),
            "BARANG_NAMA" => $nama,
            "BARANG_OPD" => $opd,
            "BARANG_KONDISI" => $kondisi,
            "BARANG_KEBERADAAN" => $keberadaan,
            "BARANG_WAKTU_PENDATAAN" => date("Y-m-d H:i:s"),
            "BARANG_FOTO" => $uploadFile,
            "BARANG_KETERANGAN" => $keterangan,
        ]);
        
        return Helper::composeReply2("SUCCESS", "Berhasil menambahkan data barang", $save);
    }


    public function barangUpdate(Request $request){
        $id = $request->id;
        $kode = $request->kode;
        $nama = $request->nama;
        $opd = $request->opd;
        $kondisi = $request->kondisi;
        $keberadaan = $request->keberadaan;
        $keterangan = $request->keterangan;
        $file = $request->foto;
        
        $cek = DB::select("
            SELECT * FROM barang WHERE BARANG_ID = ?
        ", [$id]);
        if(count($cek) == 0){
            return Helper::composeReply2("ERROR", "Barang tidak ditemukan");
        }
        $barang = $cek[0];

        $base64String = $request->base64String; // Gantilah ini dengan cara yang sesuai untuk mendapatkan string base64 dari request
        if($base64String != "-"){
            // Decode base64 string menjadi binary data
            $fileBinaryData = base64_decode($base64String);

            // Buat nama file unik
            $uploadFile = "image-" . substr(md5(date("YmdHis") . $base64String), 0, 10) . ".jpg"; // Gantilah ekstensi file sesuai kebutuhan

            // Tentukan direktori penyimpanan
            $storagePath = "storage/";

            // Simpan file di direktori penyimpanan
            file_put_contents($storagePath . $uploadFile, $fileBinaryData);

            // Lakukan pemeriksaan seperti yang dilakukan dalam kode awal untuk ukuran dan format file
            $fileSize = strlen($fileBinaryData);

            if ($fileSize > 4096000) {
                return Helper::composeReply2("ERROR", "Batas ukuran file maksimal adalah 4MB");
            }

            $fileExt = "jpg"; // Gantilah ini dengan ekstensi yang diizinkan
            if (!in_array($fileExt, ["jpg", "jpeg", "png"])) {
                return Helper::composeReply2("ERROR", 'Format file tidak diizinkan');
            }

            $filePath = public_path("storage/" . $barang->{"BARANG_FOTO"});
            if (File::exists($filePath)) {
                File::delete($filePath);
                // Optionally, you can also delete it from the storage disk if you are using the storage system
                //Storage::disk('public')->delete($uploadFile);
            }
        }else{
            $uploadFile = $barang->{"BARANG_FOTO"};
        }


        if($kode != $barang->{"BARANG_KODE_SENSUS"}){
            $cek = DB::select("
                SELECT * FROM barang WHERE BARANG_KODE_SENSUS = ? AND BARANG_ID NOT = ?
            ", [$kode, $id]);
            if(count($cek) > 0){
                return Helper::composeReply2("ERROR", "Kode sensus " . $kode . " telah digunakan");
            }
        }

        $svg = QrCode::size($this->qr_size )->generate($kode);
        $save = DB::table("barang")->where("BARANG_ID", $id)->update([
            "BARANG_KODE_SENSUS" => $kode,
            "BARANG_QR_SVG" => str_replace("\n", " ", $svg),
            "BARANG_NAMA" => $nama,
            "BARANG_OPD" => $opd,
            "BARANG_KONDISI" => $kondisi,
            "BARANG_KEBERADAAN" => $keberadaan,
            "BARANG_WAKTU_PENDATAAN" => date("Y-m-d H:i:s"),
            "BARANG_FOTO" => $uploadFile,
            "BARANG_KETERANGAN" => $keterangan,
        ]);
        
        return Helper::composeReply2("SUCCESS", "Data barang diperbarui");
    }


    public function kritikInsert(Request $req)
    {
        $user = $req->user;
        $isi = $req->isi;

        $save = DB::table("kritik")->insertGetId([
            "KRITIK_USER_ID" => $user,
            "KRITIK_ISI" => $isi
        ]);

        return Helper::composeReply2("SUCCESS", "Kritik dan saran berhasil dikirim", $save);
    }


    
    public function generateQrCode(Request $req){
        $url = 'KS.1';
        $svg = QrCode::size($this->qr_size)->generate($url);
        $data["url"] = $url;
        $data["svg"] = $svg;

        #return view('template.qr', $data);
        #return QrCode::size($this->qr_size)->generate($url);
        return response(QrCode::size($this->qr_size)->generate($url))->header('Content-type','text/plain');
    }


}