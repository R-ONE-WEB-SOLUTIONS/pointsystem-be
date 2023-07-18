import{T as m,h as f,o as a,c as g,w as s,a as o,u as e,Z as _,f as p,g as h,b as i,d as n,n as y,j as v,e as b}from"./app-ccc6f7d8.js";import{_ as x}from"./GuestLayout-a31423fb.js";import{_ as k}from"./PrimaryButton-eedb0eea.js";import"./ApplicationLogo-89b0ecfd.js";const w=i("div",{class:"mb-4 text-sm text-gray-600"}," Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another. ",-1),V={key:0,class:"mb-4 font-medium text-sm text-green-600"},B=["onSubmit"],E={class:"mt-4 flex items-center justify-between"},L={__name:"VerifyEmail",props:{status:String},setup(r){const c=r,t=m({}),d=()=>{t.post(route("verification.send"))},l=f(()=>c.status==="verification-link-sent");return(u,N)=>(a(),g(x,null,{default:s(()=>[o(e(_),{title:"Email Verification"}),w,l.value?(a(),p("div",V," A new verification link has been sent to the email address you provided during registration. ")):h("",!0),i("form",{onSubmit:b(d,["prevent"])},[i("div",E,[o(k,{class:y({"opacity-25":e(t).processing}),disabled:e(t).processing},{default:s(()=>[n(" Resend Verification Email ")]),_:1},8,["class","disabled"]),o(e(v),{href:u.route("logout"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:s(()=>[n("Log Out")]),_:1},8,["href"])])],40,B)]),_:1}))}};export{L as default};
