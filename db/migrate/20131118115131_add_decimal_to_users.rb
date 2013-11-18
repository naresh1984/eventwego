class AddDecimalToUsers < ActiveRecord::Migration
  def change
 change_column :users, :id, :decimal
  end
end
