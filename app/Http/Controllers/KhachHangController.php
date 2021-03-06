<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\KhachHangModel;
use Auth;
use Hash;
use Cart;
use DB;
use Carbon\Carbon;
class KhachHangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function payment()
    {
        //Lay noi dung cua gio hang ra
        if(Auth::guard('khachhang')->check()){
            $cartCollection = Cart::getContent();
            //dd($cartCollection);
            return view('client.payment',compact('cartCollection'));
        }
        else {
            dd('Đăng nhập mới được thanh toán');
        }
    }

    public function datHang(Request $request)
    {
        $idKhachHang = Auth::guard('khachhang')->id();
        $donHang = DB::table('donhang')->insertGetid(
            [
                'dh_nguoinhan' => $request->nguoiNhan,
                'dh_sdt' => $request->sdt,
                'dh_diachi' => $request->diaChi,
                'dh_tongtien' => Cart::getSubTotal(),
                'dh_trangthai' => 1,
                'kh_id' => $idKhachHang,
                'created_at' => Carbon::now(), //Lay gia tri hien tai
            ]
        );

        $cartCollection = Cart::getContent();
        foreach ($cartCollection as $value) {
            # code...
            $soLuongHienTai = DB::table('sanpham')->where('sp_id', $value->id)->first();
            $soLuongGiam = DB::table('sanpham')->where('sp_id',$value->id)->update(
            [
                'sp_soluong' => $soLuongHienTai->sp_soluong - $value->quantity
            ]
            );

            $chiTietDonHang = DB::table('chitietdonhang')->insert(
                [
                    'dh_id' => $donHang,
                    'sp_id' => $value->id,
                    'ctdh_giatien' => $value->price,
                    'ctdh_soluong' => $value->quantity
                ]
            );
        }
        Cart::clear();
        return redirect()->route('home-client');
    }
    public function donHang($idCus) {
        // $idCus = Auth::guard('khachhang')->id();
        $order = DB::table('donhang')->where('kh_id',$idCus)->get();
        // dd ($order);
        return view('client.order', compact('order'));
    }

    public function viewRegister(){
        return view ('client.register');
    }

    public function xuLyDangKy(Request $request)
    {
        $hoTen = $request->hoTen;
        $sdt = $request->sdt;
        $diaChi = $request->diaChi;
        $tenDangNhap = $request->tenDangNhap;
        $matKhau1 = $request->matKhau1;
        $matKhau2 = $request->matKhau2;

        if ($matKhau1 != $matKhau2)
        {
            Session::flash('alert-password','Mật khẩu không trùng khớp');
            return redirect()->back();
        }
        else
        {
            $user = new KhachHangModel();
            $user->kh_hoten = $hoTen;
            $user->kh_sdt = $sdt;
            $user->kh_diachi = $diaChi;
            $user->username = $tenDangNhap;
            $user->password = Hash::make($matKhau1);
            //Save lai
            $user->save();
            return redirect()->route('login-client');
            // dd('abc');
        }
    }

    public function viewLogin(){
        if(Auth::guard('khachhang')->check())
        {
            return redirect()->back();
        }
        return view ('client.login');
    }
    public function xulyDangNhap(Request $request){
        $username = $request->username;
        $password = $request->password;

        $arr = [
            'username' => $username,
            'password' => $password
        ];

        if (Auth::guard('khachhang')->attempt($arr)) {
            return redirect()->route('home-client');
        } else{
            dd ('Tài khoản và mật khẩu không chính xác');
        }
    }
    public function logout()
    {
        Auth::guard('khachhang')->logout();
        return redirect()->route('home-client');
    }
}
