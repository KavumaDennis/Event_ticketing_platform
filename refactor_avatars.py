import os
import re

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Pattern to match: <img src="{{ $var->profile_pic ? asset('storage/'.$var->profile_pic) : asset('default.png') }}" class="..." alt="...">
    # It can be single quotes, double quotes, with or without onerror, etc.
    # The safest way is to find `<img src="{{ $...->profile_pic...}}"...>` and replace the whole tag.

    # Regular expression to match the image tag containing profile_pic ternary
    # <img[^>]*src="\{\{\s*\$([a-zA-Z0-9_>-]+(?:->)?user)?->profile_pic[^}]*\}\}"[^>]*class="([^"]+)"[^>]*>
    
    # Actually, the user asked to replace ALL occurrences of profile_pic fetching logic to profile_photo_url.
    # If I just replace:
    # $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('default.png')
    # with
    # $user->profile_photo_url
    
    # We also need to add the rings. The user said:
    # "once a user has posted experiences, they should be previewed once someone clicks on their profile picture through out the entire website"
    # To do this safely everywhere without writing complex regex for `<img ...>` to `<x-user-avatar ...>` conversion:
    
    # Let's replace the common `<img ... src="{{ ... profile_pic ... }}" class="CLASS" ...>` with `<x-user-avatar :user="$USER" size="CLASS" />`
    
    img_pattern = re.compile(r'<img[^>]*src=[\'"]\{\{\s*\$([a-zA-Z0-9_>:-]+(?:->)?(?:user|follower|friend|creator|f|sale->user|lead))(?:\?|\s*)?->profile_pic.*?\}\}[\'"][^>]*class=[\'"]([^\'"]+)[\'"][^>]*>', re.DOTALL)
    
    def replacer(match):
        user_var = match.group(1)
        classes = match.group(2)
        # Ensure user_var doesn't have trailing ->
        if user_var.endswith('->'):
            user_var = user_var[:-2]
        return f'<x-user-avatar :user="${user_var}" size="{classes}" />'
    
    new_content = img_pattern.sub(replacer, content)

    # Some images might have class defined before src
    img_pattern2 = re.compile(r'<img[^>]*class=[\'"]([^\'"]+)[\'"][^>]*src=[\'"]\{\{\s*\$([a-zA-Z0-9_>:-]+(?:->)?(?:user|follower|friend|creator|f|sale->user|lead))(?:\?|\s*)?->profile_pic.*?\}\}[\'"][^>]*>', re.DOTALL)
    
    def replacer2(match):
        classes = match.group(1)
        user_var = match.group(2)
        if user_var.endswith('->'):
            user_var = user_var[:-2]
        return f'<x-user-avatar :user="${user_var}" size="{classes}" />'
        
    new_content = img_pattern2.sub(replacer2, new_content)

    # Also replace any remaining profile_pic calls with profile_photo_url just in case
    # ONLY where it's accessed like $user->profile_pic
    # But wait, in dashboard/profile.blade.php, profile_pic is an input name. We shouldn't replace it there.
    # We will just replace `$user->profile_pic` logic.
    
    if new_content != content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {filepath}")

for root, _, files in os.walk('resources/views'):
    for file in files:
        if file.endswith('.blade.php'):
            process_file(os.path.join(root, file))
