<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\Mobil;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                ->withSuccess('Signed in');
        }

        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function registration()
    {
        return view('auth.registration');
    }
    public function tambah()
    {
        return view('userTask.tambah');
    }

    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'sim' => $data['sim'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function dashboard()
    {
        if (Auth::check()) {
            $mobil = Mobil::all();

            return view('userTask.index', ['mobil' => $mobil]);
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }


    public function proses_tambah(Request $request)
    {
        $request->validate([
            'merek' => 'required',
            'model' => 'required',
            'nomor_plat' => 'required',
            'tarif_sewa' => 'required|numeric',
        ]);


        DB::table('mobils')->insert([
            'merek' => $request->get('merek'),
            'model' =>  $request->get('model'),
            'nomor_plat' => $request->get('nomor_plat'),
            'tarif_sewa' => $request->get('tarif_sewa'),
        ]);

        return redirect("dashboard")->withSuccess('Mobil berhasil ditambahkan');
    }

    public function cari_mobil(Request $request)
    {
        $query = $request->input('query');
        $mobil = Mobil::where('merek', 'LIKE', "%$query%")
            ->orWhere('model', 'LIKE', "%$query%")
            ->orWhere('nomor_plat', 'LIKE', "%$query%")
            ->orWhere('tarif_sewa', 'LIKE', "%$query%")
            ->get();

        return view('userTask.main', compact('mobil'));
    }
    public function pinjam_page()
    {
        $mobil = Mobil::all();
        return view('userTask.pinjam', compact('mobil'));
    }


    public function pesanMobil(Request $request)
    {

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'mobil_id' => 'required|exists:mobils,id',
        ]);


        $userId = Auth::id();

        $mobilId = $request->mobil_id;
        $tanggalMulai = $request->start_date;
        $tanggalSelesai = $request->end_date;

        $mobilTersedia = DB::table('rentals')
            ->where('mobil_id', $mobilId)
            ->where(function ($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->where(function ($query) use ($tanggalMulai, $tanggalSelesai) {
                    $query->whereBetween('start_date', [$tanggalMulai, $tanggalSelesai])
                        ->orWhereBetween('end_date', [$tanggalMulai, $tanggalSelesai]);
                })
                    ->orWhere(function ($query) use ($tanggalMulai, $tanggalSelesai) {
                        $query->where('start_date', '<=', $tanggalMulai)
                            ->where('end_date', '>=', $tanggalSelesai);
                    });
            })
            ->exists();

        if (!$mobilTersedia) {
            Rental::create([
                'start_date' => $tanggalMulai,
                'end_date' => $tanggalSelesai,
                'mobil_id' => $mobilId,
                'user_id' => $userId,
            ]);

            $mobil = Mobil::all();
            return view('userTask.index', compact('mobil'))->with('success', 'Peminjaman mobil berhasil!');
        } else {
            return redirect()->back()->with('error', 'Mobil tidak tersedia pada tanggal yang diminta.');
        }
    }
    public function daftar_sewa_user()
    {
        $userId = Auth::id();
        $mobil = Mobil::all();


        return view('userTask.sewa-user', compact('mobil'));
    }

    public function pengembalian()
    {
        $mobilPeminjaman = Rental::where('user_id', Auth::id())->get();


        $mobilIds = $mobilPeminjaman->pluck('mobil_id')->toArray();


        $mobil = Mobil::whereIn('id', $mobilIds)->get();

        return view('userTask.pengembalian', compact('mobil'));
    }

    public function daftar_pengembalian()
    {
        $mobilPeminjaman = Rental::where('user_id', Auth::id())->get();


        $mobilIds = $mobilPeminjaman->pluck('mobil_id')->toArray();


        $mobil = Mobil::whereIn('id', $mobilIds)->get();
        $pengembalians = Pengembalian::all();
        // $pengembalians = Pengembalian::where('user_id', Auth::id())->get();

        return view('userTask.daftar-pengembalian', compact('pengembalians', 'mobil'));
    }



    public function pengembalianMobil(Request $request)
    {
        $request->validate([
            'nomor_plat' => 'required|exists:mobils,nomor_plat',
            'tanggal_kembali' => 'required|date',
            'jumlah_hari' => 'required|integer|min:1',
        ]);

        $nomorPlat = $request->nomor_plat;
        $jumlahHari = $request->jumlah_hari;

        $mobil2 = Mobil::where('nomor_plat', $nomorPlat)->first();

        if (!$mobil2) {
            return redirect()->back()->with('error', 'Mobil dengan nomor plat tersebut tidak ditemukan.');
        }

        $tarifSewa = $mobil2->tarif_sewa;

        $biayaSewa = $tarifSewa * $jumlahHari;

        DB::table('pengembalians')->insert([
            'nomor_plat' => $nomorPlat,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah_hari' => $jumlahHari,
            'biaya_sewa' => $biayaSewa,
        ]);

        $mobilId = Mobil::where('nomor_plat', $nomorPlat)->value('id');

        Rental::where('mobil_id', $mobilId)
            ->where('user_id', auth()->id())
            ->delete();
        $mobil = Mobil::all();
        return view('userTask.main', compact('mobil'))->with('success', 'Peminjaman mobil berhasil!');
    }
}
