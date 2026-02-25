$u = App\Models\User::where('email', 'perusahaan@dumeg.id')->first();
if ($u) {
    echo "User: " . $u->email . "
";
    echo "Verified At: " . $u->email_verified_at . "
";
    echo "Has Verified Email: " . ($u->hasVerifiedEmail() ? 'Yes' : 'No') . "
";
} else {
    echo "User perusahaan@dumeg.id not found.
";
}

$u2 = App\Models\User::where('email', 'admin@dumeg.id')->first();
if ($u2) {
    echo "User: " . $u2->email . "
";
    echo "Verified At: " . $u2->email_verified_at . "
";
    echo "Has Verified Email: " . ($u2->hasVerifiedEmail() ? 'Yes' : 'No') . "
";
} else {
    echo "User admin@dumeg.id not found.
";
}
