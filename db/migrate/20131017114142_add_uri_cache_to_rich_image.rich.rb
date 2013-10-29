# This migration comes from rich (originally 20111117202133)
class AddUriCacheToRichImage < ActiveRecord::Migration
  def change
    add_column :events, :featured_image, :text
  end
end
