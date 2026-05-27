<?php
class DBKorisnik extends Tabela
{
    public function DaLiPostojiKorisnik($loginusername, $loginpassword)
    {
        $korisnickoIme = $this->EscapirajVrednost($loginusername);
        $sifra = $this->EscapirajVrednost($loginpassword);
        $upit = "SELECT * FROM `KORISNIK` WHERE `KORISNICKOIME`='" . $korisnickoIme . "' AND `SIFRA`='" . $sifra . "'";
        $red = $this->VratiPrviAsocijativniRed($upit);

        return $red ? 'DA' : 'NE';
    }

    public function DajKorisnika($loginusername, $loginpassword)
    {
        $korisnickoIme = $this->EscapirajVrednost($loginusername);
        $sifra = $this->EscapirajVrednost($loginpassword);
        $upit = "SELECT * FROM `KORISNIK` WHERE `KORISNICKOIME`='" . $korisnickoIme . "' AND `SIFRA`='" . $sifra . "'";

        return $this->VratiPrviAsocijativniRed($upit);
    }

    public function DajImePrijavljenogKorisnika($loginusername, $loginpassword)
    {
        $korisnik = $this->DajKorisnika($loginusername, $loginpassword);
        return $korisnik ? $korisnik['IME'] : 'Nepoznat';
    }

    public function DajPrezimePrijavljenogKorisnika($loginusername, $loginpassword)
    {
        $korisnik = $this->DajKorisnika($loginusername, $loginpassword);
        return $korisnik ? $korisnik['PREZIME'] : 'korisnik';
    }

    public function DajImePrezimePrijavljenogKorisnika($loginusername, $loginpassword)
    {
        $korisnik = $this->DajKorisnika($loginusername, $loginpassword);
        if (!$korisnik) {
            return 'Nepoznat korisnik';
        }

        return trim($korisnik['IME'] . ' ' . $korisnik['PREZIME']);
    }

    public function DajIDPrijavljenogKorisnika($loginusername, $loginpassword)
    {
        $korisnik = $this->DajKorisnika($loginusername, $loginpassword);
        if (!$korisnik) {
            return 0;
        }

        return (int) $korisnik['IDKORISNIKA'];
    }
}
