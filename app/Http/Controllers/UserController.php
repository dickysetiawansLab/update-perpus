<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Pesan;
use App\Models\Buku;


class UserController extends Controller
{
	public function index(){
		return view('Users/index');
	}
	public function edit(Request $request){
		$id = Auth::user()->id;
		$nama = Auth::user()->username;
		$users = $request -> validate([
			'username'=>'required|unique:users',
			'kode_user' =>'required|unique:users',
			'nis'=>'required|unique:users',
			'fullname'=>'required',
			'kelas'=>'required',
			'alamat'=>'required',
		]);
		$user = User::find($id);
		$user->username = $users['username'];
		$user->kode_user = $users['kode_user'];
		$user->nis = $users['nis'];
		$user->fullname = $users['fullname'];
		$user->kelas = $users['kelas'];
		$user->alamat = $users['alamat'];
		$user->update();

		return redirect('/user/{$nama}');
	}
	public function inbox(){
		$pesans =Pesan::all();
		return view('Users/inbox', [
			'pesans'=> $pesans,
		]);
	}
	public function pesan(Request $request){
		$pesan = Pesan::find($request->id);
		return view('Users/pesan', compact('pesan'));
	}

	public function draf(){
		$pesans =Pesan::all();
		return view('Users/draf', [
			'pesans'=> $pesans,
		]);
	}
	public function isi_draf(Request $request){
		$pesan = Pesan::find($request->id);
		return view('Users/isi_draf', compact('pesan'));
	}

	public function send_pesan(){
		$users = User::all();
		return view('Users/send_pesan',[
			'users'=> $users,
		]);
	}
	public function kirim(Request $request){
		$pengirims = Auth::user()->username;
		if($request['files'] == null){
			$data = $request -> validate([
				'penerima'=>'required',
				'judul_pesan'=>'required',
				'isi_pesan'=>'required',
			]);
			
			$data_pesan = [
				'penerima' => $data['penerima'],
				'pengirim' => $pengirims,
				'judul_pesan' => $data['judul_pesan'],
				'isi_pesan' => $data['isi_pesan'],
				'status' => []
			];
			if ( $data['penerima'] == $pengirims) {
				$data_pesan['status'] = 'draf';
				$request->session()->flash('Draf','Karena nama penerima sama, secara otomatis akan di masukan kedalam draf');
			}else{
				$data_pesan['status'] = 'Terkirim';
			}

			Pesan::create($data_pesan);

			return redirect('/pesan-send');
		}else{
			return back()->with('ErorPesan','Pesan Tidak bisa terkirim di karenakan ada file gambar!');
		}	
	}

	public function peminjaman(Request $request){
		$data_buku = Buku::find($request->id);
		return view('Users/pesan_pinjam_buku', compact('data_buku'));
	}
}

?>