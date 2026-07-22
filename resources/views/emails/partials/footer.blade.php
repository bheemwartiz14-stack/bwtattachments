<tr><td style="padding:0 40px;"><div style="border-top:1px solid #eef0f4;"></div></td></tr>

        <!-- Signature -->
        <tr>
          <td class="fluid-padding" style="padding:26px 40px 32px 40px;">
            <p style="margin:0 0 4px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#4b5563;">Best regards,</p>
            <p style="margin:0 0 2px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111827;">{{ $senderName }}</p>
            <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#9ca3af;"><a href="mailto:{{ $supportEmail }}" style="color:#9ca3af; text-decoration:none;">{{ $supportEmail }}</a></p>
          </td>
        </tr>

      </table>
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td class="fluid-padding" style="padding:26px 20px 40px 20px; text-align:center;">
      <p style="margin:0 0 6px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11.5px; line-height:17px; color:#9ca3af;">
        &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
      </p>
      <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11.5px; line-height:17px; color:#9ca3af;">
        <a href="{{ $appUrl }}" style="color:#9ca3af; text-decoration:none;">{{ $appUrl }}</a>
      </p>
    </td>
  </tr>

</table>

<!--[if mso]></td></tr></table><![endif]-->
</center>
</body>
</html>
